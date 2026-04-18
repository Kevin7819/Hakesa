---
name: tdd
description: >
  Test-Driven Development workflow for Laravel with Pest and PHPUnit.
  Trigger: ALWAYS when implementing features, fixing bugs, or refactoring.
  This is a MANDATORY workflow, not optional.
license: MIT
metadata:
  author: hakesa
  version: "1.0"
  scope: [root]
  auto_invoke:
    - "Implementing feature"
    - "Fixing bug"
    - "Refactoring code"
    - "Working on task"
    - "Modifying component"
allowed-tools: Read, Edit, Write, Glob, Grep, Bash, Task
---

## TDD Cycle (MANDATORY)

```
+-----------------------------------------+
|  RED -> GREEN -> REFACTOR               |
|     ^                        |          |
|     +------------------------+          |
+-----------------------------------------+
```

**The question is NOT "should I write tests?" but "what tests do I need?"**

---

## The Three Laws of TDD

1. **No production code** until you have a failing test
2. **No more test** than necessary to fail
3. **No more code** than necessary to pass

---

## Detect Your Stack

Before starting, identify your stack:

| Context | Runner | Test pattern |
|---------|--------|-------------|
| Laravel Feature Tests | Pest (`vendor/bin/pest`) | `tests/Feature/*.php` |
| Laravel Unit Tests | Pest (`vendor/bin/pest`) | `tests/Unit/*.php` |
| Livewire Component | Pest (`vendor/bin/pest`) | `tests/Feature/Livewire/*.php` |

---

## Phase 0: Assessment (ALWAYS FIRST)

Before writing ANY code:

```bash
# 1. Find existing tests for this feature
vendor/bin/pest --filter=CartTest        # By name
vendor/bin/pest tests/Feature/CartTest.php  # By file

# 2. Check coverage
vendor/bin/pest --coverage

# 3. Read existing tests to understand patterns
```

### Decision Tree

```
+------------------------------------------+
|     Does test file exist for this code?  |
+----------+-----------------------+-------+
           | NO                    | YES
           v                       v
+------------------+    +------------------+
| CREATE test     |    | Check coverage  |
| -> Phase 1: RED |    | for your change  |
+--------+-------+    +---------+---------+
                              |
                     +--------+--------+
                     | Missing cases?  |
                     +---+---------+---+
                         | YES     | NO
                         v         v
                 +-----------+ +-----------+
                 | ADD tests | | Proceed   |
                 | Phase 1   | | Phase 2   |
                 +-----------+ +-----------+
```

---

## Phase 1: RED - Write Failing Tests

### For NEW Functionality (Pest)

```php
uses(RefreshDatabase::class);

describe('Cart', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create(['price' => 1000, 'stock' => 5]);
    });

    it('can add a product to cart', function () {
        // Given - existing state
        $cartService = app(CartService::class);

        // When - action to test
        $cartService->addProduct($this->product->id, 1);

        // Then - expected outcome
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);
    });
});
```

**Run -> MUST fail:** Test references code that doesn't exist yet.

### For BUG FIXES

Write a test that **reproduces the bug** first:

```php
it('does not allow negative quantity in cart', function () {
    $response = $this->actingAs($this->user)
        ->postJson('/carrito/agregar', [
            'product_id' => $this->product->id,
            'quantity' => -5,
        ]);

    // Currently accepts negative - should FAIL
    $response->assertStatus(422);
});
```

**Run -> Should FAIL (reproducing the bug)**

### For REFACTORING

Capture ALL current behavior BEFORE refactoring:

```bash
# Run ALL existing tests - they should PASS
# This is your safety net - if any fail after refactoring, you broke something
vendor/bin/pest
```

**Run -> All should PASS (baseline)**

---

## Phase 2: GREEN - Minimum Code

Write the MINIMUM code to make the test pass. Hardcoding is valid for the first test.

```php
// Test expects negative quantity to fail
public function addProduct($productId, $quantity) {
    // FAKE IT - hardcoded is valid for first test
    if ($quantity < 0) {
        throw new \InvalidArgumentException('Quantity cannot be negative');
    }
    // ...
}
```

**This passes. But we're not done...**

---

## Phase 3: Triangulation (CRITICAL)

**One test allows faking. Multiple tests FORCE real logic.**

Add tests with different inputs that break the hardcoded value:

| Scenario | Required? |
|----------|-----------|
| Happy path | YES |
| Zero/empty values | YES |
| Boundary values | YES |
| Different valid inputs | YES (breaks fake) |
| Error conditions | YES |

```php
it('calculates 10 percent discount', function () {
    $discount = calculateDiscount(100, 10);
    expect($discount)->toBe(10);
});

// ADD - breaks the fake:
it('calculates 15 percent on 200', function () {
    $discount = calculateDiscount(200, 15);
    expect($discount)->toBe(30);
});

it('returns 0 for 0 percent rate', function () {
    $discount = calculateDiscount(100, 0);
    expect($discount)->toBe(0);
});
```

**Now fake BREAKS -> Real implementation required.**

---

## Phase 4: REFACTOR

Tests GREEN -> Improve code quality WITHOUT changing behavior.

- Extract methods
- Improve naming
- Add types/validation
- Reduce duplication

**Run tests after EACH change -> Must stay GREEN**

---

## Quick Reference

```
+------------------------------------------------+
|                 TDD WORKFLOW                    |
+------------------------------------------------+
| 0. ASSESS: What tests exist? What's missing?   |
|                                                |
| 1. RED: Write ONE failing test                 |
|    +-- Run -> Must fail with clear error       |
|                                                |
| 2. GREEN: Write MINIMUM code to pass           |
|    +-- Fake It is valid for first test         |
|                                                |
| 3. TRIANGULATE: Add tests that break the fake  |
|    +-- Different inputs, edge cases            |
|                                                |
| 4. REFACTOR: Improve with confidence           |
|    +-- Tests stay green throughout             |
|                                                |
| 5. REPEAT: Next behavior/requirement           |
+------------------------------------------------+
```

---

## Anti-Patterns (NEVER DO)

```
# Laravel/Pest:

# 1. Code first, tests after
public function newFeature() { ... }  # Then writing tests = USELESS

# 2. Skip triangulation
# Single test allows faking forever

# 3. Test implementation details
expect($cart->items)->toHaveCount(3)  # OK - behavior
expect(CartItem::class)->toBeTrue()  # BAD - implementation

# 4. All tests at once before any code
# Write ONE test, make it pass, THEN write the next

# 5. Giant test methods using act()
# Each test should verify ONE behavior

# 6. Using $this->withoutExceptionHandling() too often
# Let exceptions propagate naturally

# 7. Not using RefreshDatabase properly
# Each test needs clean database
```

---

## Commands

```bash
vendor/bin/pest                    # Run all tests
vendor/bin/pest --filter=CartTest  # Filter by name
vendor/bin/pest tests/Feature/CartTest.php  # Specific file
vendor/bin/pest --coverage        # With coverage
vendor/bin/pest --parallel       # Parallel (if installed)
vendor/bin/pest -v             # Verbose
vendor/bin/pest -w              # Watch mode (with pest-watch)
```

---

## References

- **Pest Documentation**: https://pestphp.com/docs/
- **Laravel Testing**: https://laravel.com/docs/testing
- **Faker**: https://fakerphp.github.io/faker/