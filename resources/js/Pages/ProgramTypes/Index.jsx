import React, { useState, useEffect } from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';
import DataTable from '../../Components/DataTable';
import Modal from '../../Components/Modal';

export default function Index({ programTypes }) {
    const [showCreate, setShowCreate] = useState(false);
    const [showEdit, setShowEdit] = useState(false);
    const [editItem, setEditItem] = useState(null);
    const form = useForm({ name: '', date: '' });
    const editFormData = useForm({ name: '', date: '', status: true });

    const handleCreate = (e) => {
        e.preventDefault();
        form.post('/dashboard/program-types', { onSuccess: () => { setShowCreate(false); form.reset(); } });
    };

    const handleEditClick = async (id) => {
        const res = await fetch(`/dashboard/program-types/${id}/edit`);
        const data = await res.json();
        setEditItem(data);
        editFormData.setData({ name: data.name, date: data.date, status: data.status == 1 });
        setShowEdit(true);
    };

    const handleUpdate = async (e) => {
        e.preventDefault();
        router.put(`/dashboard/program-types/${editItem.id}`, editFormData.data, {
            preserveScroll: true,
            onSuccess: () => {
                setShowEdit(false);
            }
        });
    };

    const handleDelete = async (id) => {
        if (!confirm('ডিলেট করতে চান?')) return;
        router.delete(`/dashboard/program-types/${id}`, {
            preserveScroll: true
        });
    };

    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    const columns = [
        { header: 'নং', cell: (row, idx) => idx + 1 },
        { header: 'নাম', accessor: 'name' },
        { header: 'তারিখ', accessor: 'date' },
        {
            header: 'স্টেটাস', cell: (row) => (
                <Link href={`/program-types/status/${row.id}`}>
                    <span className={`badge bg-${row.status == 1 ? 'success' : 'danger'}`}>{row.status == 1 ? 'Active' : 'Inactive'}</span>
                </Link>
            )
        },
        {
            header: 'প্রক্রিয়া', cell: (row) => (
                <div className="d-flex gap-1">
                    <button className="btn btn-sm btn-primary" onClick={() => handleEditClick(row.id)}><i className="fa fa-edit"></i></button>
                    <button className="btn btn-sm btn-danger" onClick={() => handleDelete(row.id)}><i className="fa fa-trash"></i></button>
                </div>
            )
        },
    ];

    return (
        <AdminLayout>
            <Head title="অনুষ্ঠান ধরন" />
            <Breadcrumb title="অনুষ্ঠান ধরন" items={[{ label: 'তালিকা', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header d-flex justify-content-between">
                            <h4>অনুষ্ঠান ধরন তালিকা</h4>
                            <button className="btn btn-primary btn-sm" onClick={() => setShowCreate(true)}><i className="fa fa-plus"></i> নতুন</button>
                        </div>
                        <div className="card-body"><DataTable columns={columns} data={programTypes || []} /></div>
                    </div>
                </div>
            </div>
            <Modal show={showCreate} onClose={() => setShowCreate(false)} title="নতুন অনুষ্ঠান ধরন">
                <form onSubmit={handleCreate}>
                    <div className="mb-3"><label className="form-label">নাম *</label><input type="text" className="form-control" value={form.data.name} onChange={e => form.setData('name', e.target.value)} required /></div>
                    <div className="mb-3"><label className="form-label">তারিখ *</label><input type="date" className="form-control" value={form.data.date} onChange={e => form.setData('date', e.target.value)} required /></div>
                    <button type="submit" className="btn btn-primary" disabled={form.processing}>সংরক্ষণ</button>
                </form>
            </Modal>
            <Modal show={showEdit} onClose={() => setShowEdit(false)} title="অনুষ্ঠান ধরন সম্পাদনা">
                <form onSubmit={handleUpdate}>
                    <div className="mb-3"><label className="form-label">নাম</label><input type="text" className="form-control" value={editFormData.data.name} onChange={e => editFormData.setData('name', e.target.value)} /></div>
                    <div className="mb-3"><label className="form-label">তারিখ</label><input type="date" className="form-control" value={editFormData.data.date} onChange={e => editFormData.setData('date', e.target.value)} /></div>
                    <div className="mb-3"><label><input type="checkbox" className="form-check-input me-2" checked={editFormData.data.status} onChange={e => editFormData.setData('status', e.target.checked)} /> Active</label></div>
                    <button type="submit" className="btn btn-primary">আপডেট</button>
                </form>
            </Modal>
        </AdminLayout>
    );
}
