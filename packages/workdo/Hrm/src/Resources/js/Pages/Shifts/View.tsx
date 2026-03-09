import { DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { useTranslation } from 'react-i18next';
import { Clock } from 'lucide-react';
import { Shift } from './types';
import { formatTime, formatDate } from '@/utils/helpers';

interface ViewProps {
    shift: Shift;
}

export default function View({ shift }: ViewProps) {
    const { t } = useTranslation();

    return (
        <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
            <DialogHeader className="pb-4 border-b">
                <div className="flex items-center gap-3">
                    <div className="p-2 bg-primary/10 rounded-lg">
                        <Clock className="h-5 w-5 text-primary" />
                    </div>
                    <div>
                        <DialogTitle className="text-xl font-semibold">{t('Shift Details')}</DialogTitle>
                        <p className="text-sm text-muted-foreground">{shift.shift_name}</p>
                    </div>
                </div>
            </DialogHeader>

            <div className="overflow-y-auto flex-1 p-4 space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('Shift Name')}</label>
                        <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{shift.shift_name || '-'}</p>
                    </div>
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('Night Shift')}</label>
                        <div className="bg-gray-50 p-2 rounded">
                            <span className={`px-2 py-1 rounded-full text-xs font-medium ${shift.is_night_shift ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}`}>
                                {shift.is_night_shift ? t('Yes') : t('No')}
                            </span>
                        </div>
                    </div>
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('Break Start Time')}</label>
                        <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{shift.break_start_time ? formatTime(shift.break_start_time) : '-'}</p>
                    </div>
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('Break End Time')}</label>
                        <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{shift.break_end_time ? formatTime(shift.break_end_time) : '-'}</p>
                    </div>
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('Start Time')}</label>
                        <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{shift.start_time ? formatTime(shift.start_time) : '-'}</p>
                    </div>
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('End Time')}</label>
                        <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{shift.end_time ? formatTime(shift.end_time) : '-'}</p>
                    </div>
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('Created At')}</label>
                        <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{shift.created_at ? formatDate(shift.created_at) : '-'}</p>
                    </div>
                </div>
            </div>
        </DialogContent>
    );
}