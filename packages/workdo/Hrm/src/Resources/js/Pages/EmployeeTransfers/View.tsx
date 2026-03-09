import { DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { useTranslation } from 'react-i18next';
import { ArrowRight, User, Building, Users, Calendar, FileText, Download } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { EmployeeTransfer } from './types';
import { formatDate, getImagePath } from '@/utils/helpers';

interface ViewProps {
    employeetransfer: EmployeeTransfer;
}

export default function View({ employeetransfer }: ViewProps) {
    const { t } = useTranslation();

    const getStatusColor = (status: string) => {
        const statusMap: any = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'approved': 'bg-green-100 text-green-800', 
            'in progress': 'bg-blue-100 text-blue-800',
            'completed': 'bg-green-100 text-green-800',
            'rejected': 'bg-red-100 text-red-800',
            'cancelled': 'bg-gray-100 text-gray-800'
        };
        return statusMap[status] || 'bg-gray-100 text-gray-800';
    };

    const getStatusText = (status: string) => {
        const statusMap: any = {
            'pending': 'Pending',
            'approved': 'Approved',
            'in progress': 'In Progress', 
            'completed': 'Completed',
            'rejected': 'Rejected',
            'cancelled': 'Cancelled'
        };
        return statusMap[status] || status;
    };

    return (
        <DialogContent className="max-w-4xl max-h-[90vh] overflow-y-auto">
            <DialogHeader className="pb-4 border-b">
                <DialogTitle className="flex items-center gap-2">
                    <User className="h-5 w-5" />
                    {t('Employee Transfer Details')}
                </DialogTitle>
            </DialogHeader>
            
            <div className="overflow-y-auto flex-1 p-6 space-y-6">
                {/* Employee Info */}
                <div className="bg-gray-50 p-4 rounded-lg">
                    <div className="flex items-center justify-between">
                        <div>
                            <h3 className="font-semibold text-lg">{employeetransfer.employee?.name || '-'}</h3>
                            <p className="text-sm text-gray-600">{t('Employee')}</p>
                        </div>
                        <span className={`px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(employeetransfer.status)}`}>
                            {t(getStatusText(employeetransfer.status))}
                        </span>
                    </div>
                </div>

                {/* Transfer Path Visualization */}
                <div className="bg-blue-50 p-6 rounded-lg">
                    <h4 className="font-semibold mb-4 flex items-center gap-2">
                        <ArrowRight className="h-4 w-4" />
                        {t('Transfer Path')}
                    </h4>
                    
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        {/* From */}
                        <div className="text-center">
                            <div className="bg-white p-4 rounded-lg border-2 border-red-200">
                                <Building className="h-8 w-8 mx-auto mb-2 text-red-600" />
                                <h5 className="font-semibold text-red-800">{t('From')}</h5>
                                <div className="mt-2 space-y-1 text-sm">
                                    <p><strong>{t('Branch')}:</strong> {employeetransfer.from_branch?.branch_name || '-'}</p>
                                    <p><strong>{t('Department')}:</strong> {employeetransfer.from_department?.department_name || '-'}</p>
                                    <p><strong>{t('Designation')}:</strong> {employeetransfer.from_designation?.designation_name || '-'}</p>
                                </div>
                            </div>
                        </div>

                        {/* Arrow */}
                        <div className="flex justify-center">
                            <ArrowRight className="h-8 w-8 text-blue-600" />
                        </div>

                        {/* To */}
                        <div className="text-center">
                            <div className="bg-white p-4 rounded-lg border-2 border-green-200">
                                <Building className="h-8 w-8 mx-auto mb-2 text-green-600" />
                                <h5 className="font-semibold text-green-800">{t('To')}</h5>
                                <div className="mt-2 space-y-1 text-sm">
                                    <p><strong>{t('Branch')}:</strong> {employeetransfer.to_branch?.branch_name || '-'}</p>
                                    <p><strong>{t('Department')}:</strong> {employeetransfer.to_department?.department_name || '-'}</p>
                                    <p><strong>{t('Designation')}:</strong> {employeetransfer.to_designation?.designation_name || '-'}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Transfer Summary */}
                    <div className="mt-4 text-center">
                        <p className="text-lg font-semibold text-blue-800">
                            {employeetransfer.from_branch?.branch_name || '-'} → {employeetransfer.to_branch?.branch_name || '-'}
                        </p>
                    </div>
                </div>

                {/* Transfer Details */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('Transfer Date')}</label>
                        <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{employeetransfer.transfer_date ? formatDate(employeetransfer.transfer_date) : '-'}</p>
                    </div>
                    
                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('Effective Date')}</label>
                        <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{employeetransfer.effective_date ? formatDate(employeetransfer.effective_date) : '-'}</p>
                    </div>

                    <div className="space-y-2">
                        <label className="text-sm font-medium text-gray-700">{t('Approved By')}</label>
                        <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{employeetransfer.approved_by?.name || '-'}</p>
                    </div>
                </div>

                <div className="space-y-2">
                    <label className="text-sm font-medium text-gray-700">{t('Reason')}</label>
                    <p className="text-sm text-gray-900 bg-gray-50 p-2 rounded">{employeetransfer.reason || '-'}</p>
                </div>
            </div>
        </DialogContent>
    );
}