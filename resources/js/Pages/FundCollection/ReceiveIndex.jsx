import React, { useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';
import DataTable from '../../Components/DataTable';

export default function ReceiveIndex({ fundCollections }) {
    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    const handleApprove = async (id) => {
        if (!confirm('আপনি কি এই খেদমত গ্রহণ করতে চান?')) return;
        router.post(`/fund/receive/${id}/approve`, {}, {
            preserveScroll: true
        });
    };

    const handleCancel = async (id) => {
        if (!confirm('আপনি কি এই খেদমত বাতিল করতে চান?')) return;
        router.post(`/fund/receive/${id}/cancel`, {}, {
            preserveScroll: true
        });
    };

    const columns = [
        { header: 'নং', cell: (row, idx) => idx + 1 },
        { header: 'তারিখ', accessor: 'date' },
        { header: 'খেদমত', accessor: 'khedmot_amount' },
        { header: 'মানত', accessor: 'manat_amount' },
        { header: 'কল্যাণ', accessor: 'kollan_amount' },
        { header: 'ভাড়া', accessor: 'rent_amount' },
        { header: 'মোট', accessor: 'total_amount' },
        {
            header: 'স্টেটাস', cell: (row) => {
                const colors = { pending: 'warning', collected: 'success', canceled: 'danger' };
                return <span className={`badge bg-${colors[row.status] || 'secondary'}`}>{row.status}</span>;
            }
        },
        {
            header: 'প্রক্রিয়া', cell: (row) => (
                <div className="d-flex gap-1">
                    {row.status === 'pending' && (
                        <>
                            <button className="btn btn-sm btn-success" onClick={() => handleApprove(row.id)}>গ্রহণ</button>
                            <button className="btn btn-sm btn-danger" onClick={() => handleCancel(row.id)}>বাতিল</button>
                        </>
                    )}
                </div>
            )
        },
    ];

    return (
        <AdminLayout>
            <Head title="খেদমত জমা" />
            <Breadcrumb title="খেদমত জমা" items={[{ label: 'তালিকা', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header d-flex justify-content-between">
                            <h4>খেদমত জমা তালিকা</h4>
                            <Link href="/fund_collections/receive/create" className="btn btn-primary btn-sm">
                                <i className="fa fa-plus"></i> নতুন জমা
                            </Link>
                        </div>
                        <div className="card-body">
                            <DataTable columns={columns} data={fundCollections || []} />
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
