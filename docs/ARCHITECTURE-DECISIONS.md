# CCK Architecture Decisions

Bu belge, Craft Commerce Kit için kesinleşmiş mimari kararları kaydeder.

Yeni geliştirmeler bu kararlara uygun ilerlemelidir. Bir karar değiştirilecekse önce bu belge güncellenmeli, ardından kod değişikliğine geçilmelidir.

---

## 1. Ürün Vizyonu

Craft Commerce Kit yalnızca Tilla Leather Craft için geliştirilmiş bir WooCommerce eklentisi değildir.

CCK:

- sektör bağımsız,
- marka bağımsız,
- component tabanlı,
- pack tabanlı,
- WooCommerce uyumlu

bir commerce experience sistemi olarak geliştirilir.

Aynı çekirdek farklı senaryolarda kullanılabilmelidir:

- deri ürünleri,
- kuyum,
- kahve,
- moda,
- el yapımı ürünler,
- farklı premium commerce markaları.

---

## 2. Mimari Katmanlar

```text
CCK Core
├── Contracts
├── Component System
├── Experience Renderer
├── Generic Commerce Features
├── Scenario Packs
└── Brand Packs
```

Her katmanın sorumluluğu birbirinden ayrılmalıdır.

---

## 3. CCK Core

CCK Core sektör ve marka bağımsızdır.

Core sorumlulukları:

- component registry,
- component renderer,
- component manifest sistemi,
- experience renderer,
- WooCommerce contracts,
- generic product option altyapısı,
- cart item data altyapısı,
- order item meta altyapısı,
- fiyat farkı altyapısı,
- asset yükleme altyapısı,
- generic storefront davranışları.

Core doğrudan şu kavramları bilmemelidir:

- deri rengi,
- dikiş rengi,
- kahve öğütme tipi,
- yüzük ölçüsü,
- Tilla renkleri,
- belirli bir markanın fiyatları.

---

## 4. Component Sistemi

Component yalnızca görünüm ve render sorumluluğu taşır.

Component:

- HTML çıktısını üretir,
- manifest üzerinden tanımlanır,
- renderer üzerinden çalışır,
- attributes ve bindings kabul eder,
- experience tanımlarında kullanılabilir.

Component içinde şu sorumluluklar bulunmamalıdır:

- lisans kontrolü,
- premium erişim kararı,
- sepet verisi kaydetme,
- sipariş metası oluşturma,
- kalıcı fiyatlandırma mantığı,
- markaya özel sabit veriler.

Önce temel ve generic component sistemi tamamlanacaktır.

---

## 5. Generic Product Option Components

CCK Core içinde sektöre özgü component adları kullanılmaz.

Core içinde bulunabilecek generic component örnekleri:

- product-option-swatches,
- product-option-radio,
- product-option-checkbox,
- product-option-select,
- product-option-text,
- product-option-textarea,
- product-option-details,
- delivery-timeline,
- product-actions.

`product-option-swatches` şu amaçlarda kullanılabilir:

- deri rengi,
- metal rengi,
- kumaş rengi,
- kahve kavurma seviyesi,
- seramik sır rengi.

Core seçeneğin sektörel anlamını bilmez.

---

## 6. Generic Commerce Features

İş mantığı component katmanından ayrı tutulur.

Generic feature veya service örnekleri:

- product options,
- option validation,
- price adjustment,
- cart item data,
- order item meta,
- personalization,
- delivery estimate,
- gift option,
- wishlist,
- quick view.

Sektöre ve markaya özgü değerler pack katmanlarından gelir.

---

## 7. Scenario Packs

Sektöre özgü davranışlar Scenario Pack olarak tanımlanır.

```text
packs/
├── leather/
├── jewelry/
└── coffee/
```

### Leather Pack

- leather color,
- stitch color,
- leather type,
- hardware finish,
- monogram seçenekleri,
- leather care bilgileri,
- üretim süresi varsayımları.

### Jewelry Pack

- metal type,
- ring size,
- stone selection,
- chain length,
- engraving,
- certificate,
- jewelry gift box.

### Coffee Pack

- roast level,
- grind size,
- bean origin,
- package size,
- subscription interval,
- brewing recommendation.

Bir pack yalnızca kendi sektöründe gerekli olan tanımları yüklemelidir.

Coffee Pack yüklendiğinde Leather Color seçeneği bulunmamalıdır.

---

## 8. Brand Packs

Markaya özgü ayarlar Brand Pack içinde bulunur.

İlk Brand Pack:

```text
packs/brands/tilla/
```

Tilla Pack, Leather Pack üzerine kurulacaktır:

```text
Tilla Brand Pack
└── extends: leather
```

Tilla Pack içinde bulunabilecek veriler:

- Tilla deri renkleri,
- Tilla dikiş renkleri,
- Tilla fiyat farkları,
- Tilla kişiselleştirme seçenekleri,
- Tilla teslimat metinleri,
- Tilla hediye paketi metinleri,
- Tilla component varsayılanları,
- Tilla sayfa experience tanımları,
- Tilla marka preset'i,
- Tilla görsel ve tipografi ayarları.

Markaya özgü veriler Core içine yazılmamalıdır.

---

## 9. Experience Katmanı

Experience tanımları component'lerin:

- sırasını,
- yerleşimini,
- attributes değerlerini,
- bindings değerlerini

belirler.

Örnek experience alanları:

- homepage,
- shop archive,
- single product,
- cart,
- checkout,
- thank-you.

Bir experience iş mantığı üretmez. Var olan component ve feature'ları bir araya getirir.

---

## 10. Katman Karar Kuralları

| Soru | Katman |
|---|---|
| Birden fazla sektörde kullanılabilir mi? | Core |
| Yalnızca belirli bir sektöre mi ait? | Scenario Pack |
| Belirli bir markanın renk, metin veya fiyatı mı? | Brand Pack |
| Yalnızca görünüm mü üretiyor? | Component |
| Sepet, fiyat veya sipariş davranışı mı? | Feature / Service |
| Component sırasını ve yerleşimini mi belirliyor? | Experience |

Örnek:

```text
Swatch görünümü             → Core Component
Option fiyat farkı          → Core Feature / Service
Leather Color tanımı        → Leather Pack
Dark Brown #3a2418          → Tilla Brand Pack
Ürün sayfası seçenek sırası → Tilla Experience
```

---

## 11. Legacy Option Plugins

Aşağıdaki bağımsız eklentiler hedef mimari değildir:

- CCK Leather Color Options
- CCK Personalization Options
- CCK Delivery Estimate Options
- CCK Gift Package Options
- CCK Wishlist Options
- CCK Quick View Options

Bu eklentiler:

- bağımsız ürün olarak geliştirilmeyecek,
- CCK storefront tarafından zorunlu bağımlılık olarak kullanılmayacak,
- yalnızca davranış ve kaynak kod referansı olarak değerlendirilecektir.

Her özellik şu parçalara ayrılmalıdır:

```text
Generic behavior    → CCK Core
Sector definition   → Scenario Pack
Brand configuration → Brand Pack
Visual output       → Component
Page composition    → Experience
```

---

## 12. Geliştirme Sırası

1. Core component sözleşmelerini tamamla.
2. Generic commerce component'lerini tamamla.
3. Generic commerce feature ve service altyapılarını tamamla.
4. Scenario Pack sözleşmesini oluştur.
5. Leather Pack'i oluştur.
6. Jewelry Pack'i oluştur.
7. Coffee Pack'i oluştur.
8. Tilla Brand Pack'i Leather Pack üzerine kur.
9. Legacy plugin bağımlılıklarını kaldır.
10. Legacy eklentiler kapalıyken uçtan uca test yap.

---

## 13. Çalışma Kuralları

Her geliştirme görevi:

- tek bir amaç taşımalı,
- mümkün olduğunca 1–3 dosyayla sınırlı olmalı,
- ayrı bir commit olmalı,
- belirtilmeyen alanları refactor etmemeli,
- başlamadan önce mevcut kod kanıtla incelenmeli,
- mimari varsayımla değiştirilmemelidir.

Her görev açıklamasında şu alanlar bulunmalıdır:

```text
Amaç
Kanıt
Değişecek dosyalar
Yapılacaklar
Yapılmayacaklar
Kabul kriterleri
Test komutları
Commit mesajı
```

---

## 14. Karar Değişikliği Kuralı

Bu belgedeki bir karar değiştirilecekse:

1. Önce karar tartışılır.
2. Yeni karar açık biçimde onaylanır.
3. Bu belge ayrı bir commit ile güncellenir.
4. Kod değişikliği sonraki ayrı görevde yapılır.

Kod, belgelenmiş mimari kararlardan önce değiştirilmez.
