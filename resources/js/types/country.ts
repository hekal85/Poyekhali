export interface LocalizedText {
    ar: string;
    en: string;
}

export interface VisaType {
    key: string;
    name: LocalizedText;
    fee: number;
    documents: LocalizedText[];
}

export interface Country {
    id: number;
    slug: string;
    flag: string; // ISO 3166-1 alpha-2 code, lowercase
    name: LocalizedText;
    region: 'gulf' | 'other';
    processing_time: LocalizedText;
    image_url: string | null;
    visa_types: VisaType[];
}

export function flagEmoji(iso2: string): string {
    return iso2
        .toUpperCase()
        .replace(/./g, (char) => String.fromCodePoint(127397 + char.charCodeAt(0)));
}
