<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();

interface FooterCountry { id: number; slug: string; name_ar: string; name_en: string }
interface FooterVisaType { key: string; name_ar: string; name_en: string }

const page = usePage<{ footerCountries?: FooterCountry[]; footerVisaTypes?: FooterVisaType[] }>();
</script>

<template>
    <footer class="bg-ink text-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-14 md:grid-cols-5">
            <div class="md:col-span-2">
                <span class="font-logo text-xl font-extrabold" dir="ltr">Поехали<span class="text-brass">!</span></span>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-white/70">{{ t('footer.about') }}</p>

                <!-- ملحوظة: مفيش dir="ltr" على الـ div نفسه عشان الفقرة تفضل محاذية لنفس اتجاه
                     النص اللي فوقها (يمين في العربي، شمال في الإنجليزي) - الـ dir="ltr" بس على
                     كل رقم/إيميل لوحده عشان يتقرأ صح من غير ما يقلب محاذاة السطر كله -->
                <div class="mt-4 space-y-1.5 text-sm text-white/60">
                    <p><span dir="ltr" class="inline-block">+7 993 644-58-81</span></p>
                    <p><span dir="ltr" class="inline-block">hekal_85@hotmail.com</span></p>
                    <p>Telegram: <span dir="ltr" class="inline-block">@hekal_85</span></p>
                </div>
            </div>

            <div>
                <h4 class="font-display text-sm font-bold text-brass">{{ t('footer.quick_links') }}</h4>
                <ul class="mt-4 space-y-2 text-sm text-white/70">
                    <li><Link href="/" class="hover:text-white">{{ t('nav.home') }}</Link></li>
                    <li><Link href="/countries" class="hover:text-white">{{ t('nav.countries') }}</Link></li>
                    <li><Link href="/apply" class="hover:text-white">{{ t('nav.apply') }}</Link></li>
                    <li><Link href="/track" class="hover:text-white">{{ t('nav.track') }}</Link></li>
                    <li><Link href="/contact" class="hover:text-white">{{ t('nav.contact') }}</Link></li>
                </ul>
            </div>

            <!-- دول متاحة - جاية من قاعدة البيانات (مش هاردكود) -->
            <div>
                <h4 class="font-display text-sm font-bold text-brass">{{ t('footer.available_countries') }}</h4>
                <ul class="mt-4 space-y-2 text-sm text-white/70">
                    <li v-for="c in page.props.footerCountries ?? []" :key="c.slug">
                        <Link :href="`/countries/${c.slug}`" class="hover:text-white">
                            {{ locale === 'ar' ? c.name_ar : c.name_en }}
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- أنواع التأشيرات - جاية من قاعدة البيانات، بتودي لصفحة "متاحة في كام دولة" -->
            <div>
                <h4 class="font-display text-sm font-bold text-brass">{{ t('footer.available_visa_types') }}</h4>
                <ul class="mt-4 space-y-2 text-sm text-white/70">
                    <li v-for="v in page.props.footerVisaTypes ?? []" :key="v.key">
                        <Link :href="`/visa-types/${v.key}`" class="hover:text-white">
                            {{ locale === 'ar' ? v.name_ar : v.name_en }}
                        </Link>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 px-6 py-5">
            <p class="mx-auto max-w-7xl text-xs leading-relaxed text-white/50">{{ t('footer.disclaimer') }}</p>
        </div>

        <div class="border-t border-white/10 px-6 py-4 text-center text-xs text-white/40">
            © {{ new Date().getFullYear() }} Poyekhali. {{ t('footer.rights') }}.
        </div>
    </footer>
</template>
