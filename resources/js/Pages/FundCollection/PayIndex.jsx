import React, { useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';
import DataTable from '../../Components/DataTable';

export default function PayIndex({ pays }) {
    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    const columns = [
        { header: 'নং', cell: (row, idx) => idx + 1 },
        { header: 'তারিখ', accessor: 'date' },
        { header: 'প্রদান করা হয়েছে', accessor: 'pay_to' },
        { header: 'খেদমত', accessor: 'khedmot_amount' },
        { header: 'মানত', accessor: 'manat_amount' },
        { header: 'কল্যাণ', accessor: 'kalyan_amount' },
        { header: 'ভাড়া', accessor: 'rent_amount' },
        { header: 'মোট প্রদান', accessor: 'total_paid' },
        { header: 'মন্তব্য', accessor: 'comment' },
    ];

    return (
        <AdminLayout>
            <Head title="খেদমত খরচ" />
            <Breadcrumb title="খেদমত খরচ" items={[{ label: 'তালিকা', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header d-flex justify-content-between">
                            <h4>খেদমত খরচ তালিকা</h4>
                            <Link href="/fund_collections/pay/create" className="btn btn-primary btn-sm">
                                <i className="fa fa-plus"></i> নতুন খরচ
                            </Link>
                        </div>
                        <div className="card-body">
                            <DataTable columns={columns} data={pays || []} />
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
