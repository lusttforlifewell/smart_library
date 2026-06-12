document.addEventListener('DOMContentLoaded', function() {
    // --- Toast Notification System ---
    window.showToast = function(message, type = 'info') {
        const container = document.getElementById('toast-container');
        if(!container) return;
        
        const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };
        const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
        
        const toast = document.createElement('div');
        toast.className = `${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 min-w-[250px] mb-2 fade-in`;
        toast.innerHTML = `
            <i class="fas ${icons[type] || icons.info}"></i>
            <span class="text-sm font-medium">${message}</span>
        `;
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    // --- Auto Detect URL Success/Error Params ---
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('success') === 'updated') showToast('Data berhasil diperbarui!', 'success');
    if(urlParams.get('success') === 'deleted') showToast('Data berhasil dihapus!', 'success');
    if(urlParams.get('success') === 'borrowed') showToast('Buku berhasil dipinjam!', 'success');
    if(urlParams.get('success') === 'returned') showToast('Pengembalian dikonfirmasi!', 'success');
    if(urlParams.get('success') === 'purchased') showToast('Pembelian ebook berhasil!', 'success');
    if(urlParams.get('error')) showToast(urlParams.get('error').replace(/%20/g, ' '), 'error');
});