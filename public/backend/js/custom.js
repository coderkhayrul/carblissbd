

// Update/reset Company Logo of company setting page
let companyLogo = document.getElementById('companyLogo');
const fileInputLogo = document.querySelector('.logo-file-input'),
    resetFileInputLogo = document.querySelector('.logo-image-reset');

if (companyLogo) {
    const resetLogo = companyLogo.src;
    fileInputLogo.onchange = () => {
        if (fileInputLogo.files[0]) {
            companyLogo.src = window.URL.createObjectURL(fileInputLogo.files[0]);
        }
    };
    resetFileInputLogo.onclick = () => {
        fileInputLogo.value = '';
        companyLogo.src = resetLogo;
    };
}



// Update/reset Company Favicon of company setting page
let companyFavicon = document.getElementById('companyFavicon');
const fileInputFavicon = document.querySelector('.favicon-file-input'),
    resetFileInputFavicon = document.querySelector('.favicon-image-reset');

if (companyFavicon) {
    const resetFavicon = companyFavicon.src;
    fileInputFavicon.onchange = () => {
        if (fileInputFavicon.files[0]) {
            companyFavicon.src = window.URL.createObjectURL(fileInputFavicon.files[0]);
        }
    };
    resetFileInputFavicon.onclick = () => {
        fileInputFavicon.value = '';
        companyFavicon.src = resetFavicon;
    };
}

// Update/reset Admin Favicon of company setting page
let adminFavicon = document.getElementById('adminFavicon');
const fileInputAdminFavicon = document.querySelector('#adminFaviconUpload'),
    resetFileInputAdminFavicon = document.querySelector('.admin-favicon-image-reset');

if (adminFavicon) {
    const resetAdminFavicon = adminFavicon.src;
    fileInputAdminFavicon.onchange = () => {
        if (fileInputAdminFavicon.files[0]) {
            adminFavicon.src = window.URL.createObjectURL(fileInputAdminFavicon.files[0]);
        }
    };
    resetFileInputAdminFavicon.onclick = () => {
        fileInputAdminFavicon.value = '';
        adminFavicon.src = resetAdminFavicon;
    };
}

// Update/reset Authentication Background of company setting page
let authBg = document.getElementById('authBg');
const fileInputAuthBg = document.querySelector('#authBgUpload'),
    resetFileInputAuthBg = document.querySelector('.auth-bg-image-reset');

if (authBg) {
    const resetAuthBg = authBg.src;
    fileInputAuthBg.onchange = () => {
        if (fileInputAuthBg.files[0]) {
            authBg.src = window.URL.createObjectURL(fileInputAuthBg.files[0]);
        }
    };
    resetFileInputAuthBg.onclick = () => {
        fileInputAuthBg.value = '';
        authBg.src = resetAuthBg;
    };
}


