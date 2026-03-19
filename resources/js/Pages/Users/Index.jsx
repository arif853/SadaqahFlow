import React, { useState, useEffect } from 'react';
import { Head, Link, useForm, usePage, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';
import Breadcrumb from '../../Components/Breadcrumb';
import DataTable from '../../Components/DataTable';
import Modal from '../../Components/Modal';

export default function Index({ users, roles, members }) {
    const { auth } = usePage().props;
    const [showCreate, setShowCreate] = useState(false);
    const [showEdit, setShowEdit] = useState(false);
    const [showAssign, setShowAssign] = useState(false);
    const [editUser, setEditUser] = useState(null);
    const [assignUser, setAssignUser] = useState(null);
    const [assignedMembers, setAssignedMembers] = useState([]);
    const [selectedMembers, setSelectedMembers] = useState([]);

    const createForm = useForm({
        name: '', username: '', address: '', phone: '', email: '',
        bloodType: '', password: '', role: ''
    });

    const editForm = useForm({
        name: '', username: '', address: '', phone: '', email: '',
        bloodType: '', password: '', role: ''
    });

    const handleCreate = (e) => {
        e.preventDefault();
        createForm.post('/users', { onSuccess: () => { setShowCreate(false); createForm.reset(); } });
    };

    const handleEditClick = async (id) => {
        const res = await fetch(`/users/${id}/edit`);
        const data = await res.json();
        setEditUser(data.user);
        editForm.setData({
            name: data.user.name || '', username: data.user.username || '',
            address: data.user.address || '', phone: data.user.phone || '',
            email: data.user.email || '', bloodType: data.user.bloodType || '',
            password: '', role: data.user.roles?.[0]?.name || ''
        });
        setShowEdit(true);
    };

    const handleUpdate = async (e) => {
        e.preventDefault();
        router.put(`/users/${editUser.id}`, editForm.data, {
            preserveScroll: true,
            onSuccess: () => {
                setShowEdit(false);
            }
        });
    };

    const handleDelete = async (id) => {
        if (!confirm('ডিলেট করতে চান?')) return;
        router.delete(`/users/${id}`, {
            preserveScroll: true
        });
    };

    const handleAssignClick = async (id) => {
        const res = await fetch(`/users/members/${id}/get-member`);
        const data = await res.json();
        setAssignUser(data.user);
        setAssignedMembers(data.members || []);
        setSelectedMembers([]);
        setShowAssign(true);
    };

    const handleAssignSubmit = async () => {
        if (selectedMembers.length === 0) return;
        router.post(`/users/members/${assignUser.id}/assign`, { members: selectedMembers }, {
            preserveScroll: true,
            onSuccess: () => {
                setShowAssign(false);
            }
        });
    };

    const handleRemoveMember = async (memberId) => {
        router.delete(`/users/assign-member/remove/${memberId}/${assignUser.id}`, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                setAssignedMembers(prev => prev.filter(m => m.id !== memberId));
            }
        });
    };

    useEffect(() => { if (typeof feather !== 'undefined') feather.replace(); });

    const columns = [
        { header: 'নং', cell: (row, idx) => idx + 1 },
        { header: 'নাম', accessor: 'name' },
        { header: 'ইউজারনেম', accessor: 'username' },
        { header: 'ফোন', accessor: 'phone' },
        { header: 'ঠিকানা', accessor: 'address' },
        { header: 'রোল', cell: (row) => (row.roles || []).map(r => r.name).join(', ') },
        {
            header: 'স্টেটাস', cell: (row) => (
                <Link href={`/users/status/${row.id}`}>
                    <span className={`badge bg-${row.status == 1 ? 'success' : 'danger'}`}>{row.status == 1 ? 'Active' : 'Inactive'}</span>
                </Link>
            )
        },
        {
            header: 'প্রক্রিয়া', cell: (row) => (
                <div className="d-flex gap-1">
                    <button className="btn btn-sm btn-info" onClick={() => handleAssignClick(row.id)}><i className="fa fa-users"></i></button>
                    <button className="btn btn-sm btn-primary" onClick={() => handleEditClick(row.id)}><i className="fa fa-edit"></i></button>
                    <button className="btn btn-sm btn-danger" onClick={() => handleDelete(row.id)}><i className="fa fa-trash"></i></button>
                </div>
            )
        },
    ];

    return (
        <AdminLayout>
            <Head title="ইউজার" />
            <Breadcrumb title="ইউজার" items={[{ label: 'তালিকা', active: true }]} />
            <div className="theme-body" data-simplebar>
                <div className="custom-container">
                    <div className="card">
                        <div className="card-header d-flex justify-content-between">
                            <h4>ইউজার তালিকা</h4>
                            <button className="btn btn-primary btn-sm" onClick={() => setShowCreate(true)}>
                                <i className="fa fa-plus"></i> নতুন ইউজার
                            </button>
                        </div>
                        <div className="card-body"><DataTable columns={columns} data={users || []} /></div>
                    </div>
                </div>
            </div>

            {/* Create */}
            <Modal show={showCreate} onClose={() => setShowCreate(false)} title="নতুন ইউজার" size="lg">
                <form onSubmit={handleCreate}>
                    <div className="row">
                        {[
                            { label: 'নাম *', field: 'name', type: 'text', required: true },
                            { label: 'ইউজারনেম *', field: 'username', type: 'text', required: true },
                            { label: 'ফোন *', field: 'phone', type: 'text', required: true },
                            { label: 'ঠিকানা', field: 'address', type: 'text' },
                            { label: 'ইমেইল', field: 'email', type: 'email' },
                            { label: 'রক্তের গ্রুপ', field: 'bloodType', type: 'text' },
                            { label: 'পাসওয়ার্ড *', field: 'password', type: 'password', required: true },
                        ].map((f) => (
                            <div className="col-md-6 mb-3" key={f.field}>
                                <label className="form-label">{f.label}</label>
                                <input type={f.type} className="form-control" value={createForm.data[f.field]} onChange={e => createForm.setData(f.field, e.target.value)} required={f.required} />
                            </div>
                        ))}
                        <div className="col-md-6 mb-3">
                            <label className="form-label">রোল *</label>
                            <select className="form-control" value={createForm.data.role} onChange={e => createForm.setData('role', e.target.value)} required>
                                <option value="">নির্বাচন করুন</option>
                                {(roles || []).map(r => <option key={r.id} value={r.name}>{r.name}</option>)}
                            </select>
                        </div>
                    </div>
                    <button type="submit" className="btn btn-primary" disabled={createForm.processing}>সংরক্ষণ</button>
                </form>
            </Modal>

            {/* Edit */}
            <Modal show={showEdit} onClose={() => setShowEdit(false)} title="ইউজার সম্পাদনা" size="lg">
                <form onSubmit={handleUpdate}>
                    <div className="row">
                        {[
                            { label: 'নাম', field: 'name', type: 'text' },
                            { label: 'ইউজারনেম', field: 'username', type: 'text' },
                            { label: 'ফোন', field: 'phone', type: 'text' },
                            { label: 'ঠিকানা', field: 'address', type: 'text' },
                            { label: 'ইমেইল', field: 'email', type: 'email' },
                            { label: 'রক্তের গ্রুপ', field: 'bloodType', type: 'text' },
                            { label: 'পাসওয়ার্ড (ফাঁকা রাখলে পরিবর্তন হবে না)', field: 'password', type: 'password' },
                        ].map((f) => (
                            <div className="col-md-6 mb-3" key={f.field}>
                                <label className="form-label">{f.label}</label>
                                <input type={f.type} className="form-control" value={editForm.data[f.field]} onChange={e => editForm.setData(f.field, e.target.value)} />
                            </div>
                        ))}
                        <div className="col-md-6 mb-3">
                            <label className="form-label">রোল</label>
                            <select className="form-control" value={editForm.data.role} onChange={e => editForm.setData('role', e.target.value)}>
                                <option value="">নির্বাচন করুন</option>
                                {(roles || []).map(r => <option key={r.id} value={r.name}>{r.name}</option>)}
                            </select>
                        </div>
                    </div>
                    <button type="submit" className="btn btn-primary">আপডেট</button>
                </form>
            </Modal>

            {/* Assign Members */}
            <Modal show={showAssign} onClose={() => setShowAssign(false)} title={`সদস্য যোগ - ${assignUser?.name || ''}`} size="lg">
                <h6>বর্তমান সদস্যরা:</h6>
                {assignedMembers.length > 0 ? (
                    <ul className="list-group mb-3">
                        {assignedMembers.map(m => (
                            <li key={m.id} className="list-group-item d-flex justify-content-between">
                                {m.name} ({m.kollan_id})
                                <button className="btn btn-sm btn-danger" onClick={() => handleRemoveMember(m.id)}><i className="fa fa-times"></i></button>
                            </li>
                        ))}
                    </ul>
                ) : <p className="text-muted mb-3">কোনো সদস্য নেই</p>}

                <h6>নতুন সদস্য যোগ করুন:</h6>
                <select className="form-control mb-3" multiple value={selectedMembers} onChange={e => setSelectedMembers(Array.from(e.target.selectedOptions, o => o.value))}>
                    {(members || []).map(m => <option key={m.id} value={m.id}>{m.name} ({m.kollan_id})</option>)}
                </select>
                <button className="btn btn-primary" onClick={handleAssignSubmit}>যোগ করুন</button>
            </Modal>
        </AdminLayout>
    );
}
