document.querySelector("#dltALL").addEventListener('click', function(e) {
    e.preventDefault(); 

    let ans = prompt("Are you sure? All personal data will be lost. If you want to delete account type 'YES/yes'");
    if (ans !== null && ans.trim().toUpperCase() === 'YES') {
        document.getElementById("dltForm").submit();
    }
});