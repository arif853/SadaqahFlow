import React, { useEffect, useRef } from 'react';

export default function Modal({ show, onClose, title, children, size = '' }) {
    const modalRef = useRef(null);

    useEffect(() => {
        if (show) {
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
        } else {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
        }
        return () => {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
        };
    }, [show]);

    if (!show) return null;

    const modalClass = size ? `modal-dialog modal-${size}` : 'modal-dialog';

    return (
        <>
            <div className="modal fade show" style={{ display: 'block' }} tabIndex="-1" ref={modalRef}>
                <div className={modalClass}>
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title">{title}</h5>
                            <button type="button" className="btn-close" onClick={onClose}></button>
                        </div>
                        <div className="modal-body">
                            {children}
                        </div>
                    </div>
                </div>
            </div>
            <div className="modal-backdrop fade show" onClick={onClose}></div>
        </>
    );
}
