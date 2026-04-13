<?php

use App\Models\Order;

describe('Unit Example', function () {
    it('Order::generateOrderNumber follows GC- format', function () {
        $number = Order::generateOrderNumber();

        expect($number)->toStartWith('GC-');
        // Format: GC-YYYYMMDD-XXXXXXXX
        expect($number)->toMatch('/^GC-\d{8}-[A-F0-9]{8}$/');
    });

    it('Order::generateOrderNumber produces unique values', function () {
        $numbers = collect(range(1, 10))->map(fn () => Order::generateOrderNumber());

        expect($numbers->unique()->count())->toBe(10);
    });
});
