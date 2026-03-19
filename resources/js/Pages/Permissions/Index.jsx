import React, { useState, useEffect } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';
import DataTable from '../../Components/DataTable';
import Modal from '../../Components/Modal';

export default function Index({ permissions }) {
    const [showCreate, setShowCreate] = useState(false);
    const [showEdit, setShowEdit] = useState(false);
    const [editPerm, setEditPerm] = useState(null);
    const form = useForm({ name: '' });
    const editFormData = useForm({ permission_name: '' });

    const handleCreate = (e) => {
        e.preventDefault();
        form.post('/dashboard/users/permissions', { onSuccess: () => { setShowCreate(false); form.reset(); } });
    };

    const handleEditClick = async (id) => {
        const res = await fetch(`/dashboard/users/permissions/${id}/edit`);
        const data = await res.json();
        setEditPerm(data.permission);
        editFormData.setData('permission_name', data.permission.name);
        setShowEdit(true);
    };

    const handleUpdate = async (e) => {
        e.preventDefault();
        router.put(`/dashboard/users/permissions/${editPerm.id}`, editFormData.data, {
            preserveScroll: true,
            onSuccess: () => {
                setShowEdit(false);
            }
        });
    };

    const handleDelete = async (id) => {
        if (!confirm('ডিলেট করতে চান?')) return;
        router.delete(`/dashboard/users/permissions/${id}`, {
            preserveScroll: true
        });
    };

    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    const columns = [
        { header: 'নং', cell: (row, idx) => idx + 1 },
        { header: 'নাম', accessor: 'name' },
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
            <Head title="পারমিশন" />
            <Breadcrumb title="পারমিশন" items={[{ label: 'তালিকা', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header d-flex justify-content-between">
                            <h4>পারমিশন তালিকা</h4>
                            <button className="btn btn-primary btn-sm" onClick={() => setShowCreate(true)}><i className="fa fa-plus"></i> নতুন পারমিশন</button>
                        </div>
                        <div className="card-body"><DataTable columns={columns} data={permissions || []} /></div>
                    </div>
                </div>
            </div>
            <Modal show={showCreate} onClose={() => setShowCreate(false)} title="নতুন পারমিশন">
                <form onSubmit={handleCreate}>
                    <div className="mb-3">
                        <label className="form-label">পারমিশন নাম *</label>
                        <input type="text" className="form-control" value={form.data.name} onChange={e => form.setData('name', e.target.value)} required />
                    </div>
                    <button type="submit" className="btn btn-primary" disabled={form.processing}>সংরক্ষণ</button>
                </form>
            </Modal>
            <Modal show={showEdit} onClose={() => setShowEdit(false)} title="পারমিশন সম্পাদনা">
                <form onSubmit={handleUpdate}>
                    <div className="mb-3">
                        <label className="form-label">পারমিশন নাম</label>
                        <input type="text" className="form-control" value={editFormData.data.permission_name} onChange={e => editFormData.setData('permission_name', e.target.value)} />
                    </div>
                    <button type="submit" className="btn btn-primary">আপডেট</button>
                </form>
            </Modal>
        </AdminLayout>
    );
}
