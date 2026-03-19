import React, { useState, useMemo } from 'react';

export default function DataTable({ columns, data, id = 'dataTable', searchable = true }) {
    const [search, setSearch] = useState('');
    const [sortCol, setSortCol] = useState(null);
    const [sortDir, setSortDir] = useState('asc');
    const [currentPage, setCurrentPage] = useState(1);
    const perPage = 25;

    const filteredData = useMemo(() => {
        if (!search) return data || [];
        return (data || []).filter(row =>
            columns.some(col => {
                const val = col.accessor ? (typeof col.accessor === 'function' ? col.accessor(row) : row[col.accessor]) : '';
                return String(val || '').toLowerCase().includes(search.toLowerCase());
            })
        );
    }, [data, search, columns]);

    const sortedData = useMemo(() => {
        if (sortCol === null) return filteredData;
        const col = columns[sortCol];
        if (!col || !col.accessor || typeof col.accessor === 'function') return filteredData;
        return [...filteredData].sort((a, b) => {
            const aVal = a[col.accessor] || '';
            const bVal = b[col.accessor] || '';
            if (sortDir === 'asc') return String(aVal).localeCompare(String(bVal));
            return String(bVal).localeCompare(String(aVal));
        });
    }, [filteredData, sortCol, sortDir, columns]);

    const totalPages = Math.ceil(sortedData.length / perPage);
    const paginatedData = sortedData.slice((currentPage - 1) * perPage, currentPage * perPage);

    const handleSort = (idx) => {
        if (sortCol === idx) {
            setSortDir(sortDir === 'asc' ? 'desc' : 'asc');
        } else {
            setSortCol(idx);
            setSortDir('asc');
        }
    };

    return (
        <div>
            {searchable && (
                <div className="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div></div>
                    <div className="datatable-search-wrap">
                        <input
                            type="text"
                            className="form-control form-control-sm"
                            placeholder="Search..."
                            value={search}
                            onChange={e => { setSearch(e.target.value); setCurrentPage(1); }}
                            style={{ width: '100%', minWidth: '220px' }}
                        />
                    </div>
                </div>
            )}
            <div className="table-responsive">
                <table className="table" id={id}>
                    <thead>
                        <tr>
                            {columns.map((col, idx) => (
                                <th key={idx} onClick={() => handleSort(idx)} style={{ cursor: 'pointer' }}>
                                    {col.header}
                                    {sortCol === idx && (sortDir === 'asc' ? ' ▲' : ' ▼')}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {paginatedData.length === 0 ? (
                            <tr><td colSpan={columns.length} className="text-center">No data available</td></tr>
                        ) : (
                            paginatedData.map((row, rowIdx) => (
                                <tr key={row.id || rowIdx}>
                                    {columns.map((col, colIdx) => (
                                        <td key={colIdx}>
                                            {col.cell
                                                ? col.cell(row, (currentPage - 1) * perPage + rowIdx)
                                                : (typeof col.accessor === 'function'
                                                    ? col.accessor(row)
                                                    : row[col.accessor])}
                                        </td>
                                    ))}
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
            {totalPages > 1 && (
                <div className="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                    <div className="small">Showing {(currentPage - 1) * perPage + 1} to {Math.min(currentPage * perPage, sortedData.length)} of {sortedData.length} entries</div>
                    <nav>
                        <ul className="pagination pagination-sm mb-0">
                            <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                                <button className="page-link" onClick={() => setCurrentPage(p => p - 1)}>Previous</button>
                            </li>
                            {Array.from({ length: Math.min(totalPages, 5) }, (_, i) => {
                                let page;
                                if (totalPages <= 5) page = i + 1;
                                else if (currentPage <= 3) page = i + 1;
                                else if (currentPage >= totalPages - 2) page = totalPages - 4 + i;
                                else page = currentPage - 2 + i;
                                return (
                                    <li key={page} className={`page-item ${currentPage === page ? 'active' : ''}`}>
                                        <button className="page-link" onClick={() => setCurrentPage(page)}>{page}</button>
                                    </li>
                                );
                            })}
                            <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
                                <button className="page-link" onClick={() => setCurrentPage(p => p + 1)}>Next</button>
                            </li>
                        </ul>
                    </nav>
                </div>
            )}
        </div>
    );
}
