import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useTranslation } from 'react-i18next';
import { useEffect } from 'react';

interface AddonProps {
    data: any;
    getSectionData: (key: string) => any;
    updateSectionData: (key: string, updates: any) => void;
    updateSectionVisibility: (key: string, visible: boolean) => void;
}

export default function Addon({ data, getSectionData, updateSectionData, updateSectionVisibility }: AddonProps) {
    const { t } = useTranslation();
    const sectionData = getSectionData('addons') || {};
    const isVisible = data.config_sections?.section_visibility?.addons !== false;

    useEffect(() => {
        // Initialize addon settings with default values if they don't exist
        const defaultSettings = {
            title: 'Premium Addons',
            subtitle: 'Extend your BOJERI with powerful premium modules designed to enhance your business operations',
            per_page: 20,
            default_price_type: 'monthly',
            card_variant: 'card1',
            show_search: true,
            show_category: true,
            show_price: true,
            show_sort: true,
            empty_message: 'No addons available. Check back later for new premium addons and modules.'
        };

        // Only update if addon section is empty or missing keys
        const currentData = getSectionData('addons');
        const hasAllKeys = Object.keys(defaultSettings).every(key => key in currentData);

        if (!hasAllKeys) {
            updateSectionData('addons', { ...defaultSettings, ...currentData });
        }
    }, []);

    return (
        <div className="space-y-6">
            <Card>
                <CardHeader>
                    <div className="flex items-center justify-between">
                        <CardTitle>{t('Add-Ons Page Settings')}</CardTitle>
                    </div>
                </CardHeader>
                <CardContent className="space-y-6">
                    {/* Page Title and Card Variant */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                            <Label htmlFor="addons-title">{t('Page Title')}</Label>
                            <Input
                                id="addons-title"
                                value={sectionData.title || 'Premium Addons'}
                                onChange={(e) => updateSectionData('addons', { title: e.target.value })}
                                placeholder={t('Enter page title')}
                            />
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="addons-card-variant">{t('Card Variant')}</Label>
                            <Select
                                value={sectionData.card_variant || 'card1'}
                                onValueChange={(value) => updateSectionData('addons', { card_variant: value })}
                            >
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="card1">{t('Overlapping')}</SelectItem>
                                    <SelectItem value="card2">{t('Modern Gradient')}</SelectItem>
                                    <SelectItem value="card3">{t('Premium Glass')}</SelectItem>
                                    <SelectItem value="card4">{t('Horizontal')}</SelectItem>
                                    <SelectItem value="card5">{t('Colorful Floating')}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    {/* Page Subtitle and Empty State Message */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                            <Label htmlFor="addons-subtitle">{t('Page Subtitle')}</Label>
                            <Textarea
                                id="addons-subtitle"
                                value={sectionData.subtitle || 'Extend your BOJERI with powerful premium modules designed to enhance your business operations'}
                                onChange={(e) => updateSectionData('addons', { subtitle: e.target.value })}
                                placeholder={t('Enter page subtitle')}
                                rows={3}
                            />
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="addons-empty-message">{t('Empty State Message')}</Label>
                            <Textarea
                                id="addons-empty-message"
                                value={sectionData.empty_message || 'No addons available. Check back later for new premium addons and modules.'}
                                onChange={(e) => updateSectionData('addons', { empty_message: e.target.value })}
                                placeholder={t('Message to show when no addons are found')}
                                rows={3}
                            />
                        </div>
                    </div>

                    {/* Items Per Page and Default Price Type */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-2">
                            <Label htmlFor="addons-per-page">{t('Items Per Page')}</Label>
                            <Input
                                id="addons-per-page"
                                type="number"
                                min="1"
                                max="50"
                                value={sectionData.per_page || 20}
                                onChange={(e) => updateSectionData('addons', { per_page: parseInt(e.target.value) || 20 })}
                                placeholder={t('Number of addons per page')}
                            />
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="addons-price-type">{t('Default Price Type')}</Label>
                            <Select
                                value={sectionData.default_price_type || 'monthly'}
                                onValueChange={(value) => updateSectionData('addons', { default_price_type: value })}
                            >
                                <SelectTrigger>
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="monthly">{t('Monthly')}</SelectItem>
                                    <SelectItem value="yearly">{t('Yearly')}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    {/* Show Filters */}
                    <div className="space-y-4">
                        <Label>{t('Filter Options')}</Label>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="flex items-center space-x-2">
                                <Switch
                                    id="show-search"
                                    checked={sectionData.show_search !== false}
                                    onCheckedChange={(checked) => updateSectionData('addons', { show_search: checked })}
                                />
                                <Label htmlFor="show-search">{t('Show Search')}</Label>
                            </div>
                            <div className="flex items-center space-x-2">
                                <Switch
                                    id="show-price"
                                    checked={sectionData.show_price !== false}
                                    onCheckedChange={(checked) => updateSectionData('addons', { show_price: checked })}
                                />
                                <Label htmlFor="show-price">{t('Show Price Filter')}</Label>
                            </div>
                            <div className="flex items-center space-x-2">
                                <Switch
                                    id="show-sort"
                                    checked={sectionData.show_sort !== false}
                                    onCheckedChange={(checked) => updateSectionData('addons', { show_sort: checked })}
                                />
                                <Label htmlFor="show-sort">{t('Show Sort Options')}</Label>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    );
}
