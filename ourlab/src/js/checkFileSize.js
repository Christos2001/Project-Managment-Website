
document.querySelector('#myForm').onsubmit = function(e) {
    const fileInput = document.querySelector('input[name="Descfile"]');
    
    if (fileInput.files.length > 0) {
        const fileSize = fileInput.files[0].size; 
        const maxSize = 2 * 1024 * 1024; 

        if (fileSize > maxSize) {
            alert("The file is too big.Max size is 2MB");
            e.preventDefault(); 
        }
    }
};
