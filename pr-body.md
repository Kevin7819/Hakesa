## Summary

This PR consolidates multiple improvements made during the project audit session:

### 1. AI Agent Skills Installation (8 skills)
- accessibility: WCAG 2.2 audit guidelines
- copywriting: Marketing copy frameworks
- laravel-patterns: Laravel architecture patterns
- laravel-specialist: Laravel 10+ development
- nodejs-backend-patterns: Node.js services
- php-pro: PHP 8.3+ strict typing
- tailwind-css-patterns: Tailwind utilities
- vite: Vite 8 build tool

Skills installed to .agents/, .claude/, .qwen/ for multi-agent support.

### 2. Stock Management Refactor
- Removed stock column from products table (migration)
- Removed stock validation from ProductRequest, CartController, CartApiController, PlaceOrderService
- Added fixed quantity limit (max 10 instead of stock-based)
- Updated all views to remove stock UI
- Updated tests to use new quantity limits

### 3. GitHub Issue Templates
- Added bug_report.yml template with pre-flight checks
- Added feature_request.yml template for enhancements

### 4. Project Documentation
- Created CLAUDE.md with AI agent instructions
- Added .atl/skill-registry.md with compact rules
- Added skills-lock.json for dependency tracking

---

## Changes Table

| Type | Files | Description |
|------|-------|-------------|
| feat(skills) | 49 | Added 8 AI development skills |
| refactor | 18 | Removed stock, added quantity limits |
| chore | 3 | Issue templates + CLAUDE.md |
| db | 1 | Migration to drop stock column |

---

## Issues Linked

- Closes #68: Remove stock management
- Closes #69: Quantity selector with max 10
- Closes #70: Notes field for orders (already existed)

---

## Testing

All tests updated to reflect new behavior:
- tests/Feature/CartTest.php - Updated stock assertions to quantity
- tests/Feature/CheckoutTest.php - Updated stock checks to quantity limits

Run tests: composer test