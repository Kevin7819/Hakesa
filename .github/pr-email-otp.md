## Summary

This PR adds a complete email notification system and a secure OTP-based password reset flow, replacing the legacy token-based password reset from Laravel Breeze.

### Email Notifications (#50)
- Order confirmation emails sent automatically after checkout
- Welcome emails sent on new user registration
- Markdown email templates with Hakesa branding
- Queue-based email delivery (async, non-blocking)
- Comprehensive tests for mailables and email sending

### OTP Password Reset (#51)
- 6-digit OTP code sent via email for password reset
- Atomic OTP verification (prevents race conditions)
- Rate limiting: 3 OTP requests/hour, 5 verification attempts/10min
- OTP codes hashed in database (like passwords)
- Single-use OTPs with 10-minute expiration
- No email enumeration (same response for valid/invalid email)
- 3-step flow: Request OTP > Verify Code > Set New Password

---

## Architecture

### Email System
```
CheckoutController > Mail::queue(OrderConfirmation) > Queue Worker > SMTP
RegisteredUserController > Mail::queue(WelcomeEmail) > Queue Worker > SMTP
```

### OTP Flow
```
User > /forgot-password > OtpService.generateAndSend() > Mail::queue(OtpVerification)
User > /verify-otp > OtpService.verify() (atomic: check + mark used)
User > /reset-password/{token} > Set new password > Session cleared
```

---

## Changes

### New Files (11)
| File | Purpose | Lines |
|------|---------|-------|
| `app/Mail/OrderConfirmation.php` | Order confirmation mailable | 60 |
| `app/Mail/WelcomeEmail.php` | Welcome email mailable | 57 |
| `app/Mail/OtpVerification.php` | OTP code email mailable | 58 |
| `app/Models/PasswordResetOtp.php` | OTP record model | 38 |
| `app/Services/OtpService.php` | OTP generation, verification, lifecycle | 85 |
| `app/Http/Controllers/OtpPasswordResetController.php` | Full OTP flow controller | 180 |
| `database/migrations/*_create_password_reset_otps_table.php` | OTP table migration | 31 |
| `resources/views/emails/orders/confirmed.blade.php` | Order confirmation template | 37 |
| `resources/views/emails/welcome.blade.php` | Welcome email template | 21 |
| `resources/views/emails/auth/otp-verification.blade.php` | OTP email template | 20 |
| `tests/Feature/OtpPasswordResetTest.php` | OTP flow tests (19 tests) | 203 |

### Modified Files (8)
| File | Change |
|------|--------|
| `app/Http/Controllers/CheckoutController.php` | Added Mail import + queue OrderConfirmation after checkout |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | Added Mail import + queue WelcomeEmail after registration |
| `routes/auth.php` | Replaced legacy password reset routes with OTP flow routes |
| `resources/views/auth/forgot-password-otp.blade.php` | New: Email input form for OTP request |
| `resources/views/auth/verify-otp.blade.php` | New: 6-digit code input form |
| `resources/views/auth/reset-password-otp.blade.php` | New: New password form |
| `tests/Feature/Auth/PasswordResetTest.php` | Updated for OTP flow (was testing old token flow) |
| `tests/Feature/MailTest.php` | New: Email mailable tests (4 tests) |

---

## Security Features

| Feature | Implementation |
|---------|---------------|
| **OTP atomicity** | `verify()` uses `lockForUpdate()` + marks as used in single operation |
| **Race condition prevention** | DB transaction wraps OTP invalidation + creation |
| **Rate limiting (OTP requests)** | 3 per email per hour (app-level + route-level) |
| **Rate limiting (OTP verification)** | 5 per email per 10 minutes (app-level + route-level) |
| **Rate limiting (resend)** | Same 3/hour limit as initial request |
| **OTP hashing** | Codes stored with `Hash::make()` (bcrypt), never plaintext |
| **Single-use OTPs** | `used_at` timestamp set on successful verification |
| **Expiration** | 10-minute TTL on all OTP codes |
| **No email enumeration** | Same response for valid/invalid email on OTP request |
| **Session-bound reset** | Password reset token stored in session, validated on each step |
| **CSRF protection** | All forms protected by Laravel's CSRF middleware |

---

## Test Plan

```bash
vendor/bin/pest
```

**Result: 199 passed, 1 incomplete, 0 failed** (480 assertions)

### Test Coverage Breakdown
| Area | Tests | Assertions |
|------|-------|------------|
| OtpService (unit) | 8 | 16 |
| OTP Flow (integration) | 11 | 27 |
| Email Mailables | 4 | 9 |
| PasswordResetTest (OTP) | 4 | 8 |

### Security Tests Included
- OTP cannot be reused after verification
- Password reset blocked without OTP verification
- Password reset blocked with mismatched token
- Rate limiting on OTP requests (3/hour)
- Rate limiting on OTP verification (5/10min)
- Email enumeration prevention
- Expired OTP rejection
- Invalid OTP code rejection

---

## Configuration Required

Set these in `.env` for production:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hakesa.com
MAIL_FROM_NAME="${APP_NAME}"
```

For development, use `MAIL_MAILER=log` to write emails to `storage/logs/laravel.log`.

---

## Linked Issues

- Closes #50 - Email notifications with SMTP
- Closes #51 - OTP verification for password reset

---

## Judgment Day Results

This PR was reviewed by 2 independent judges. All confirmed issues were fixed:

| Finding | Status |
|---------|--------|
| Broken route in OTP email template | Fixed |
| Race condition in OTP verification | Fixed (atomic verify + mark used) |
| resendOtp missing rate limiting | Fixed (same 3/hour limit) |
| resetPassword missing verification check | Fixed (otp_reset_verified guard) |
| generateAndSend missing DB transaction | Fixed (wrapped in transaction) |
| verify() iterating all OTPs | Fixed (latest only with lockForUpdate) |
| Missing tests for security-critical paths | Fixed (6 new tests added) |
