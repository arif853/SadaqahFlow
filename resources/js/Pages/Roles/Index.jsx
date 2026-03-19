import React, { useState, useEffect } from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';
import DataTable from '../../Components/DataTable';
import Modal from '../../Components/Modal';

export default function Index({ roles }) {
    const [showCreate, setShowCreate] = useState(false);
    const [showEdit, setShowEdit] = useState(false);
    const [editRole, setEditRole] = useState(null);
    const form = useForm({ name: '' });
    const editFormData = useForm({ role_name: '' });

    const handleCreate = (e) => {
        e.preventDefault();
        form.post('/dashboard/users/roles', { onSuccess: () => { setShowCreate(false); form.reset(); } });
    };

    const handleEditClick = async (id) => {
        const res = await fetch(`/dashboard/users/roles/${id}/edit`);
        const data = await res.json();
        setEditRole(data.role);
        editFormData.setData('role_name', data.role.name);
        setShowEdit(true);
    };

    const handleUpdate = async (e) => {
        e.preventDefault();
        router.put(`/dashboard/users/roles/${editRole.id}`, editFormData.data, {
            preserveScroll: true,
            onSuccess: () => {
                setShowEdit(false);
            }
        });
    };

    const handleDelete = async (id) => {
        if (!confirm('ডিলেট করতে চান?')) return;
        router.delete(`/dashboard/users/roles/${id}`, {
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
                    <Link href={`/dashboard/users/roles/${row.id}/give-permissions`} className="btn btn-sm btn-warning"><i className="fa fa-key"></i></Link>
                    <button className="btn btn-sm btn-primary" onClick={() => handleEditClick(row.id)}><i className="fa fa-edit"></i></button>
                    <button className="btn btn-sm btn-danger" onClick={() => handleDelete(row.id)}><i className="fa fa-trash"></i></button>
                </div>
            )
        },
    ];

    return (
        <AdminLayout>
            <Head title="রোল" />
            <Breadcrumb title="রোল" items={[{ label: 'তালিকা', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header d-flex justify-content-between">
                            <h4>রোল তালিকা</h4>
                            <button className="btn btn-primary btn-sm" onClick={() => setShowCreate(true)}><i className="fa fa-plus"></i> নতুন রোল</button>
                        </div>
                        <div className="card-body"><DataTable columns={columns} data={roles || []} /></div>
                    </div>
                </div>
            </div>
            <Modal show={showCreate} onClose={() => setShowCreate(false)} title="নতুন রোল">
                <form onSubmit={handleCreate}>
                    <div className="mb-3">
                        <label className="form-label">রোল নাম *</label>
                        <input type="text" className="form-control" value={form.data.name} onChange={e => form.setData('name', e.target.value)} required />
                    </div>
                    <button type="submit" className="btn btn-primary" disabled={form.processing}>সংরক্ষণ</button>
                </form>
            </Modal>
            <Modal show={showEdit} onClose={() => setShowEdit(false)} title="রোল সম্পাদনা">
                <form onSubmit={handleUpdate}>
                    <div className="mb-3">
                        <label className="form-label">রোল নাম</label>
                        <input type="text" className="form-control" value={editFormData.data.role_name} onChange={e => editFormData.setData('role_name', e.target.value)} />
                    </div>
                    <button type="submit" className="btn btn-primary">আপডেট</button>
                </form>
            </Modal>
        </AdminLayout>
    );
}
