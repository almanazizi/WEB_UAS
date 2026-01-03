# Step 3: Tentukan Methods

## 🎯 Tujuan
Mendefinisikan methods/operations untuk setiap class, fokus pada public methods yang meaningful.

---

## 📦 Methods untuk Models

### **1. User**
```
+ bookings(): HasMany
+ tickets(): HasMany
+ approvedVisitors(): HasMany
+ isSuperadmin(): bool
+ isStaff(): bool
+ isUser(): bool
```

### **2. Visitor** ⭐
```
+ approver(): BelongsTo
+ getPhotoUrlAttribute(): string
+ scopePending(query): Builder
+ scopeApproved(query): Builder
+ scopeActive(query): Builder
+ isPending(): bool
+ isApproved(): bool
+ isActive(): bool
+ isCompleted(): bool
```

### **3. Booking**
```
+ user(): BelongsTo
+ lab(): BelongsTo
+ visitors(): HasMany
+ scopeActive(query): Builder
+ scopePending(query): Builder
+ scopeApproved(query): Builder
```

### **4. BookingVisitor**
```
+ booking(): BelongsTo
```

### **5. Lab**
```
+ assets(): HasMany
+ bookings(): HasMany
```

### **6. Asset**
```
+ lab(): BelongsTo
+ tickets(): HasMany
```

### **7. Ticket**
```
+ user(): BelongsTo
+ asset(): BelongsTo
+ scopeOpen(query): Builder
+ scopeResolved(query): Builder
```

---

## 🎮 Methods untuk Controllers

### **1. GuestVisitorController**
```
+ showCheckInForm(): View
+ store(Request): RedirectResponse
- storePhoto(photo): string
- notifyStaff(visitor): void
```

### **2. StaffDashboardController**
```
+ index(Request): View
+ notifications(): View
+ visitorDetail(id): View
+ approve(id): RedirectResponse
+ reject(id): RedirectResponse
+ checkOutVisitor(id): RedirectResponse
+ exportExcel(): Download
+ exportPDF(): Download
```

---

## 💡 Tips untuk Karya Ilmiah

**Methods yang Perlu Ditampilkan:**
- ✅ **Relationship methods** (belongsTo, hasMany) - penting untuk relasi
- ✅ **Scope queries** (scopePending, scopeActive) - query filters
- ✅ **Helper methods** (isPending, isActive) - business logic
- ✅ **Public controller actions** - endpoint API/web

**Methods yang TIDAK Perlu:**
- ❌ Getter/setter standar (getId, setName)
- ❌ Constructor jika tidak ada logic khusus
- ❌ Framework boilerplate (toArray, toJson)
- ❌ Private methods yang tidak penting

**Visibility:**
- `+` = public
- `-` = private
- `#` = protected
