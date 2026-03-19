import React, { useState, useEffect } from 'react';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';
import DataTable from '../../Components/DataTable';
import Modal from '../../Components/Modal';

export default function Index({ khedmots: initialKhedmots, members, users: allUsers }) {
    const { auth } = usePage().props;
    const roles = auth?.user?.roles || [];
    const isAdmin = roles.includes('Super Admin') || roles.includes('Admin');

    const [khedmots, setKhedmots] = useState(initialKhedmots || []);
    const [showCreate, setShowCreate] = useState(false);
    const [showEdit, setShowEdit] = useState(false);
    const [editItem, setEditItem] = useState(null);
    const [searchDate, setSearchDate] = useState('');
    const [searchName, setSearchName] = useState('');
    const [searchUser, setSearchUser] = useState('');

    const createForm = useForm({
        date: '', member_id: '', program_id: '', other_program_name: '',
        khedmot_amount: '', manat_amount: '', comment: ''
    });

    const editForm = useForm({
        date: '', member_id: '', program_id: '', other_program_name: '',
        khedmot_amount: '', manat_amount: '', comment: '', _method: 'PUT'
    });

    const handleCreate = (e) => {
        e.preventDefault();
        createForm.post('/khedmots', {
            preserveScroll: true,
            onSuccess: () => { setShowCreate(false); createForm.reset(); }
        });
    };

    const handleEditClick = async (id) => {
        const res = await fetch(`/khedmots/${id}/edit`);
        const data = await res.json();
        setEditItem(data);
        editForm.setData({
            date: data.date || '', member_id: data.member_id || '',
            program_id: data.program_id || '', other_program_name: data.other_program_name || '',
            khedmot_amount: data.khedmot_amount || '', manat_amount: data.manat_amount || '',
            comment: data.comment || '', _method: 'PUT'
        });
        setShowEdit(true);
    };

    const handleUpdate = async (e) => {
        e.preventDefault();
        router.put(`/khedmots/${editItem.id}`, editForm.data, {
            preserveScroll: true,
            onSuccess: () => {
                setShowEdit(false);
            }
        });
    };

    const handleDelete = async (id) => {
        if (!confirm('আপনি কি এই খেদমত ডিলেট করতে চান?')) return;
        router.delete(`/khedmots/${id}`, {
            preserveScroll: true
        });
    };

    const handleSearch = async () => {
        const params = new URLSearchParams();
        if (searchDate) params.append('date', searchDate);
        if (searchName) params.append('name', searchName);
        if (searchUser) params.append('userid', searchUser);
        const res = await fetch(`/khedmots/khedmot-search/search?${params}`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await res.json();
        setKhedmots(data);
    };

    const resetSearch = () => {
        setSearchDate(''); setSearchName(''); setSearchUser('');
        setKhedmots(initialKhedmots || []);
    };

    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    const columns = [
        { header: 'নং', cell: (row, idx) => idx + 1 },
        { header: 'জাকের নাম', cell: (row) => row.member?.name || '' },
        { header: 'কল্যাণ নং', cell: (row) => row.member?.kollan_id || '' },
        { header: 'তারিখ', accessor: 'date' },
        { header: 'অনুষ্ঠান', cell: (row) => row.program?.name || '' },
        { header: 'খেদমত', accessor: 'khedmot_amount' },
        { header: 'মানত', accessor: 'manat_amount' },
        { header: 'কল্যাণ', accessor: 'kalyan_amount' },
        { header: 'ভাড়া', accessor: 'rent_amount' },
        { header: 'কর্মী', cell: (row) => row.user?.name || '' },
        {
            header: 'জমা', cell: (row) => (
                <span className={`badge bg-${row.is_collected ? 'success' : 'warning'}`}>
                    {row.is_collected ? 'জমা' : 'অজমা'}
                </span>
            )
        },
        {
            header: 'প্রক্রিয়া', cell: (row) => (
                <div className="d-flex gap-1">
                    <button className="btn btn-sm btn-primary" onClick={() => handleEditClick(row.id)}>
                        <i className="fa fa-edit"></i>
                    </button>
                    <button className="btn btn-sm btn-danger" onClick={() => handleDelete(row.id)}>
                        <i className="fa fa-trash"></i>
                    </button>
                </div>
            )
        },
    ];

    return (
        <AdminLayout>
            <Head title="খেদমত" />
            <Breadcrumb title="খেদমত" items={[{ label: 'তালিকা', active: true }]} />

            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    {/* Search */}
                    <div className="card mb-3">
                        <div className="card-body">
                            <div className="row g-2 align-items-end">
                                <div className="col-md-3">
                                    <label className="form-label">তারিখ</label>
                                    <input type="date" className="form-control" value={searchDate} onChange={e => setSearchDate(e.target.value)} />
                                </div>
                                <div className="col-md-3">
                                    <label className="form-label">নাম / কল্যাণ নং</label>
                                    <input type="text" className="form-control" value={searchName} onChange={e => setSearchName(e.target.value)} />
                                </div>
                                {isAdmin && (
                                    <div className="col-md-3">
                                        <label className="form-label">কর্মী</label>
                                        <select className="form-control" value={searchUser} onChange={e => setSearchUser(e.target.value)}>
                                            <option value="">সবাই</option>
                                            {(allUsers || []).map(u => <option key={u.id} value={u.id}>{u.name}</option>)}
                                        </select>
                                    </div>
                                )}
                                <div className="col-md-3">
                                    <button className="btn btn-primary me-2" onClick={handleSearch}>সার্চ</button>
                                    <button className="btn btn-secondary" onClick={resetSearch}>রিসেট</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="card">
                        <div className="card-header d-flex justify-content-between">
                            <h4>খেদমত তালিকা</h4>
                            <button className="btn btn-primary btn-sm" onClick={() => setShowCreate(true)}>
                                <i className="fa fa-plus"></i> নতুন খেদমত
                            </button>
                        </div>
                        <div className="card-body">
                            <DataTable columns={columns} data={khedmots} />
                        </div>
                    </div>
                </div>
            </div>

            {/* Create Modal */}
            <Modal show={showCreate} onClose={() => setShowCreate(false)} title="নতুন খেদমত যোগ করুন" size="lg">
                <form onSubmit={handleCreate}>
                    <div className="row">
                        <div className="col-md-6 mb-3">
                            <label className="form-label">তারিখ *</label>
                            <input type="date" className="form-control" value={createForm.data.date} onChange={e => createForm.setData('date', e.target.value)} required />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">জাকের *</label>
                            <select className="form-control" value={createForm.data.member_id} onChange={e => createForm.setData('member_id', e.target.value)} required>
                                <option value="">নির্বাচন করুন</option>
                                {(members || []).map(m => <option key={m.id} value={m.id}>{m.name} ({m.kollan_id})</option>)}
                            </select>
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">অনুষ্ঠান *</label>
                            <select className="form-control" value={createForm.data.program_id} onChange={e => createForm.setData('program_id', e.target.value)} required>
                                <option value="">নির্বাচন করুন</option>
                            </select>
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">খেদমত পরিমাণ</label>
                            <input type="number" className="form-control" value={createForm.data.khedmot_amount} onChange={e => createForm.setData('khedmot_amount', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">মানত পরিমাণ</label>
                            <input type="number" className="form-control" value={createForm.data.manat_amount} onChange={e => createForm.setData('manat_amount', e.target.value)} />
                        </div>
                        <div className="col-md-12 mb-3">
                            <label className="form-label">মন্তব্য</label>
                            <textarea className="form-control" value={createForm.data.comment} onChange={e => createForm.setData('comment', e.target.value)} />
                        </div>
                    </div>
                    <button type="submit" className="btn btn-primary" disabled={createForm.processing}>
                        {createForm.processing ? 'Saving...' : 'সংরক্ষণ করুন'}
                    </button>
                </form>
            </Modal>

            {/* Edit Modal */}
            <Modal show={showEdit} onClose={() => setShowEdit(false)} title="খেদমত সম্পাদনা" size="lg">
                <form onSubmit={handleUpdate}>
                    <div className="row">
                        <div className="col-md-6 mb-3">
                            <label className="form-label">তারিখ *</label>
                            <input type="date" className="form-control" value={editForm.data.date} onChange={e => editForm.setData('date', e.target.value)} required />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">খেদমত পরিমাণ *</label>
                            <input type="number" className="form-control" value={editForm.data.khedmot_amount} onChange={e => editForm.setData('khedmot_amount', e.target.value)} />
                        </div>
                        <div className="col-md-6 mb-3">
                            <label className="form-label">মানত পরিমাণ</label>
                            <input type="number" className="form-control" value={editForm.data.manat_amount} onChange={e => editForm.setData('manat_amount', e.target.value)} />
                        </div>
                        <div className="col-md-12 mb-3">
                            <label className="form-label">মন্তব্য</label>
                            <textarea className="form-control" value={editForm.data.comment} onChange={e => editForm.setData('comment', e.target.value)} />
                        </div>
                    </div>
                    <button type="submit" className="btn btn-primary">আপডেট করুন</button>
                </form>
            </Modal>
        </AdminLayout>
    );
}
