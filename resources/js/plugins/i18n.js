import AR from "@/locale/ar.json";
import EN from "@/locale/en.json";
import { createI18n } from "vue-i18n";

const i18n = createI18n({
    legacy: false,
    globalInjection: true,
    locale: import.meta.env.VITE_APP_LOCALE || "EN",
    fallbackLocale: import.meta.env.VITE_APP_FALLBACK_LOCALE || "EN",
    messages: {
        EN: EN,
        AR: AR,
    },
});

const $t = i18n.global.t;

export { $t, i18n };
