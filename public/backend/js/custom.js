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


