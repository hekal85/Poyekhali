#Requires -Version 5.1
<#
    update-v4.ps1
    -------------
    التحديث الرابع لموقع بيخالي:
    - جرس إشعارات + قائمة مستخدم بعد تسجيل الدخول (لوحة التحكم / حسابك / طلباتك / خروج)
    - Modal نجاح تقديم الطلب برقم الطلب واضح + رابط نسخ/تتبع
    - كاروسيل هيرو أوضح (وبقى JS بسيط بدل حسابات CSS) - أي عدد صور تحطه يشتغل تلقائي
    - بحث وفلاتر في شاشة إدارة الطلبات (رقم طلب/جواز/اسم + حالة + دولة + نوع تأشيرة)
    - إشعار المستخدم (إيميل + جرس + تيليجرام/واتساب لو متاح) عند تغيير حالة طلبه
    - فوتر ديناميكي بالكامل: الدول وأنواع التأشيرات جايين من قاعدة البيانات
    - صفحة "أنواع التأشيرات" العامة (متاحة في كام دولة + قدم طلب)
    - أكورديون لأنواع التأشيرة في صفحة تعديل الدولة (بعنوان الـ key)
    - إحصائيات الهيرو حية من قاعدة البيانات (دول، طلبات منفذة، عملاء، سنين خبرة من 2023)
    - لوحة تحكم للعميل (/dashboard): طلباته + إشعاراته + زرار طلب جديد
    - إصلاح محاذاة بيانات التواصل في الفوتر عشان تفضل صح في كل اللغات

    الاستخدام (من جذر مشروع Laravel):
      powershell -ExecutionPolicy Bypass -File "المسار\update-v4.ps1" -ZipPath "المسار\poyekhali-visa-v4-kit.zip"
#>

param(
    [string]$ZipPath = ".\poyekhali-visa-v4-kit.zip",
    [string]$ProjectRoot = "."
)

$ErrorActionPreference = "Stop"

function Write-Step($msg) { Write-Host ""; Write-Host "==> $msg" -ForegroundColor Cyan }
function Write-Ok($msg) { Write-Host "    OK: $msg" -ForegroundColor Green }
function Write-Warn($msg) { Write-Host "    تنبيه: $msg" -ForegroundColor Yellow }

Write-Step "التأكد من مسار المشروع"
if (-not (Test-Path (Join-Path $ProjectRoot "artisan"))) {
    Write-Host "خطأ: مش لاقي ملف artisan في '$ProjectRoot'. شغّل السكريبت من جذر مشروع Laravel." -ForegroundColor Red
    exit 1
}
Write-Ok "المشروع موجود في: $((Resolve-Path $ProjectRoot).Path)"

Write-Step "التأكد من ملف الحزمة (kit)"
if (-not (Test-Path $ZipPath)) {
    Write-Host "خطأ: مش لاقي poyekhali-visa-v4-kit.zip في '$ZipPath'." -ForegroundColor Red
    exit 1
}
Write-Ok "لقيت الحزمة: $ZipPath"

Write-Step "فك ضغط الملفات"
$tempExtract = Join-Path $env:TEMP ("poyekhali-v4-" + [guid]::NewGuid())
Expand-Archive -Path $ZipPath -DestinationPath $tempExtract -Force
Write-Ok "اتفكت في: $tempExtract"

# ملحوظة مهمة: الملف ده ممكن يكون موجود بالفعل عندك (لو مشروعك جه بستارتر كيت Laravel
# الرسمي لـ Inertia). هنسأل قبل ما نستبدله عشان منمسحش أي تخصيص عندك فيه.
$handleInertiaSource = Join-Path $tempExtract "app\Http\Middleware\HandleInertiaRequests.php"
$handleInertiaDest = Join-Path $ProjectRoot "app\Http\Middleware\HandleInertiaRequests.php"
$skipHandleInertia = $false

if (Test-Path $handleInertiaDest) {
    Write-Host ""
    Write-Host "لقيت عندك ملف HandleInertiaRequests.php موجود بالفعل." -ForegroundColor Yellow
    $answer = Read-Host "استبدله بالنسخة الجديدة؟ (لو عندك تعديلات خاصة فيه، اختار N وادمج يدويًا) [y/N]"
    if ($answer -ne "y" -and $answer -ne "Y") {
        $skipHandleInertia = $true
        Write-Warn "اتخطى - راجع الملف يدويًا وضيف مفتاحي 'footerCountries' و 'footerVisaTypes' و 'unreadNotificationsCount' زي الشرح في الـ README."
    }
}

Write-Step "نسخ الملفات داخل المشروع"
Get-ChildItem -Path $tempExtract -Recurse -File | ForEach-Object {
    $relativePath = $_.FullName.Substring($tempExtract.Length).TrimStart('\', '/')

    if ($skipHandleInertia -and $relativePath -eq "app\Http\Middleware\HandleInertiaRequests.php") {
        return
    }

    $destPath = Join-Path $ProjectRoot $relativePath
    $destDir = Split-Path $destPath -Parent
    if (-not (Test-Path $destDir)) { New-Item -ItemType Directory -Path $destDir -Force | Out-Null }
    Copy-Item -Path $_.FullName -Destination $destPath -Force
    Write-Host "    نسخ: $relativePath"
}
Write-Ok "اتنسخت كل الملفات"

Push-Location $ProjectRoot
try {
    Write-Step "تشغيل الميجريشنز الجديدة (إضافية بس)"
    php artisan migrate --force
    Write-Ok "تم"

    Write-Step "مسح الكاش"
    php artisan optimize:clear
    Write-Ok "تم"
} catch {
    Write-Warn "حصل خطأ أثناء تشغيل أوامر artisan: $($_.Exception.Message)"
    Write-Warn "شغّل يدويًا: php artisan migrate --force"
}
Pop-Location

Remove-Item -Path $tempExtract -Recurse -Force -ErrorAction SilentlyContinue

Write-Host ""
Write-Host "=========================================" -ForegroundColor Green
Write-Host " خطوات يدوية متبقية" -ForegroundColor Green
Write-Host "=========================================" -ForegroundColor Green
if ($skipHandleInertia) {
    Write-Host "1. راجع app/Http/Middleware/HandleInertiaRequests.php يدويًا (اتخطى في النسخ) -"
    Write-Host "   ضيف جوه دالة share() المفاتيح: footerCountries, footerVisaTypes, unreadNotificationsCount"
    Write-Host "   (شوف الملف اللي جوه الحزمة كمرجع)."
} else {
    Write-Host "1. لو أول مرة تركّب الملف ده، تأكد إنه مسجّل في bootstrap/app.php ضمن middleware الـ web group"
    Write-Host "   (لو مشروعك بيستخدم ستارتر كيت Laravel الرسمي، غالبًا مسجّل بالفعل تلقائيًا)."
}
Write-Host "2. حط صورك العشرة في: public/images/hero/ (1.jpg لغاية 10.jpg أو أي عدد)"
Write-Host "   وعدّل مصفوفة heroSlides في resources/js/pages/Home.vue (تعليق واضح فوقها بيوضحلك بالظبط)."
Write-Host "3. شغّل: npm run dev"
Write-Host ""
Write-Host "التفاصيل الكاملة لكل نقطة في ملف الـ README المرفق."
Write-Host ""
