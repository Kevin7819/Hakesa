### Description
Allow authenticated users to save products to a wishlist for later purchase. This increases conversion rates by letting users bookmark items they're interested in.

### Scope
- Create `wishlists` table (user_id, product_id, created_at)
- Add/remove products from wishlist via AJAX
- Wishlist icon in navbar with counter
- Wishlist page (/mis-favoritos) showing saved products
- "Add to cart" directly from wishlist
- Product card shows heart icon (filled if in wishlist)
- Auto-remove products that become inactive or out of stock

### Database Schema
```sql
CREATE TABLE wishlists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
);
```

### Routes
- `GET /mis-favoritos` — Wishlist page
- `POST /favoritos/agregar/{product}` — Add to wishlist
- `DELETE /favoritos/{product}` — Remove from wishlist

### Affected Area
Scripts (setup, installation)

### Alternatives Considered
- Session-based wishlist: lost on logout, not persistent
- LocalStorage wishlist: not synced across devices
