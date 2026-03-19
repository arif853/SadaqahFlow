import React, { useState, useEffect } from 'react';
import { Head, Link, useForm, usePage, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';
import DataTable from '../../Components/DataTable';
import Modal from '../../Components/Modal';

export default function Index({ members }) {
    const { auth } = usePage().props;
    const roles = auth?.user?.roles || [];
    const isAdmin = roles.includes('Super Admin') || roles.includes('Admin');

    const [showCreate, setShowCreate] = useState(false);
    const [showEdit, setShowEdit] = useState(false);
    const [editMember, setEditMember] = useState(null);

    const createForm = useForm({
        name: '', nickName: '', father_name: '', phone: '',
        spouse_name: '', kollan_id: '', kollan_khedmot: '',
        bloodType: '', image: null
    });

    const editForm = useForm({
        name: '', nickName: '', father_name: '', phone: '',
        spouse_name: '', kollan_id: '', kollan_khedmot: '',
        bloodType: '', status: true, image2: null,
        _method: 'PUT'
    });

    const handleCreate = (e) => {
        e.preventDefault();
        createForm.post('/members', {
            preserveScroll: true,
            onSuccess: () => { setShowCreate(false); createForm.reset(); }
        });
    };

    const handleEditClick = async (id) => {
        const res = await fetch(`/members/${id}/edit`);
        const data = await res.json();
        setEditMember(data);
        editForm.setData({
            name: data.name || '', nickName: data.nickName || '',
            father_name: data.father_name || '', phone: data.phone || '',
            spouse_name: data.spouse_name || '', kollan_id: data.kollan_id || '',
            kollan_khedmot: data.kollan_khedmot || '', bloodType: data.bloodType || '',
            status: data.status == 1, image2: null, _method: 'PUT'
        });
        setShowEdit(true);
    };

    const handleUpdate = async (e) => {
        e.preventDefault();
        router.post(`/members/${editMember.id}`, editForm.data, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                setShowEdit(false);
            }
        });
    };

    const handleDelete = async (id) => {
        if (!confirm('আপনি কি এই জাকের ডিলেট করতে চান?')) return;
        router.delete(`/members/${id}`, {
            preserveScroll: true
        });
    };

    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    const columns = [
        { header: 'নং', cell: (row, idx) => idx + 1 },
        {
            header: 'ছবি', cell: (row) => row.image ? (
                <img src={`/storage/${row.image}`} width="40" height="40" className="rounded-50" alt="" />
            ) : <span>-</span>
        },
        { header: 'নাম', accessor: 'name' },
        { header: 'ডাক নাম', accessor: 'nickName' },
        { header: 'কল্যাণ নং', accessor: 'kollan_id' },
        { header: 'ফোন', accessor: 'phone' },
        { header: 'রক্তের গ্রুপ', accessor: 'bloodType' },
        {
            header: 'স্টেটাস', cell: (row) => (
                <Link href={`/members/status/${row.id}`}>
                    <span className={`badge bg-${row.status == 1 ? 'success' : 'danger'}`}>
                        {row.status == 1 ? 'Active' : 'Inactive'}
                    </span>
                </Link>
            )
        },
        {
            header: 'প্রক্রিয়া', cell: (row) => (
                <div className="d-flex gap-1">
                    <Link href={`/members/${row.id}`} className="btn btn-sm btn-info"><i className="fa fa-eye"></i></Link>
                    <button className="btn btn-sm btn-primary" onClick={() => handleEditClick(row.id)}><i className="fa fa-edit"></i></button>
                    {isAdmin && <button className="btn btn-sm btn-danger" onClick={() => handleDelete(row.id)}><i className="fa fa-trash"></i></button>}
                </div>
            )
        },
    ];

    return (
        <AdminLayout>
            <Head title="জাকের" />
            <Breadcrumb title="জাকের" items={[{ label: 'তালিকা', active: true }]} />

            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header d-flex justify-content-between">
                            <h4>জাকের তালিকা</h4>
                            <button className="btn btn-primary btn-sm" onClick={() => setShowCreate(true)}>
                                <i className="fa fa-plus"></i> নতুন জাকের
                            </button>
                        </div>
                        <div className="card-body">
                            <DataTable columns={columns} data={members || []} />
                        </div>
                    </div>
                </div>
            </div>

            {/* Create Modal */}
            <Modal show={showCreate} onClose={() => setShowCreate(false)} title="নতুন জাকের যোগ করুন" size="lg">
                <form onSubmit={handleCreate} encType="multipart/form-data">
                    <div className="row">
                        <div className="col-md-6 mb-3">
                            <label className="form-label">নাম *</label>
                            <input type="text" className="form-control" value={createForm.data.name} onChange={e => createForm.setData('name', e.target.value)} required />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">ডাক নাম</label>
                            <input type="text" className="form-control" value={createForm.data.nickName} onChange={e => createForm.setData('nickName', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">পিতার নাম</label>
                            <input type="text" className="form-control" value={createForm.data.father_name} onChange={e => createForm.setData('father_name', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">ফোন</label>
                            <input type="text" className="form-control" value={createForm.data.phone} onChange={e => createForm.setData('phone', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">স্ত্রীর নাম</label>
                            <input type="text" className="form-control" value={createForm.data.spouse_name} onChange={e => createForm.setData('spouse_name', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">কল্যাণ নং *</label>
                            <input type="text" className="form-control" value={createForm.data.kollan_id} onChange={e => createForm.setData('kollan_id', e.target.value)} required />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">কল্যাণ খেদমত</label>
                            <input type="number" className="form-control" value={createForm.data.kollan_khedmot} onChange={e => createForm.setData('kollan_khedmot', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">রক্তের গ্রুপ</label>
                            <input type="text" className="form-control" value={createForm.data.bloodType} onChange={e => createForm.setData('bloodType', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">ছবি</label>
                            <input type="file" className="form-control" accept="image/*" onChange={e => createForm.setData('image', e.target.files[0])} />
                        </div>
                    </div>
                    <button type="submit" className="btn btn-primary" disabled={createForm.processing}>
                        {createForm.processing ? 'Saving...' : 'সংরক্ষণ করুন'}
                    </button>
                </form>
            </Modal>

            {/* Edit Modal */}
            <Modal show={showEdit} onClose={() => setShowEdit(false)} title="জাকের সম্পাদনা" size="lg">
                <form onSubmit={handleUpdate} encType="multipart/form-data">
                    <div className="row">
                        <div className="col-md-6 mb-3">
                            <label className="form-label">নাম *</label>
                            <input type="text" className="form-control" value={editForm.data.name} onChange={e => editForm.setData('name', e.target.value)} required />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">ডাক নাম</label>
                            <input type="text" className="form-control" value={editForm.data.nickName} onChange={e => editForm.setData('nickName', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">পিতার নাম</label>
                            <input type="text" className="form-control" value={editForm.data.father_name} onChange={e => editForm.setData('father_name', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">ফোন</label>
                            <input type="text" className="form-control" value={editForm.data.phone} onChange={e => editForm.setData('phone', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">স্ত্রীর নাম</label>
                            <input type="text" className="form-control" value={editForm.data.spouse_name} onChange={e => editForm.setData('spouse_name', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">কল্যাণ নং *</label>
                            <input type="text" className="form-control" value={editForm.data.kollan_id} onChange={e => editForm.setData('kollan_id', e.target.value)} required />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">কল্যাণ খেদমত</label>
                            <input type="number" className="form-control" value={editForm.data.kollan_khedmot} onChange={e => editForm.setData('kollan_khedmot', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">রক্তের গ্রুপ</label>
                            <input type="text" className="form-control" value={editForm.data.bloodType} onChange={e => editForm.setData('bloodType', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">ছবি</label>
                            <input type="file" className="form-control" accept="image/*" onChange={e => editForm.setData('image2', e.target.files[0])} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-check-label">
                                <input type="checkbox" className="form-check-input" checked={editForm.data.status} onChange={e => editForm.setData('status', e.target.checked)} /> Active
                            </label>
                        </div>
                    </div>
                    <button type="submit" className="btn btn-primary">আপডেট করুন</button>
                </form>
            </Modal>
        </AdminLayout>
    );
}
