function $(x){return document.querySelectorAll(x)}


let list = $('.project')

let htitle = $(".title")
let hstatus = $(".status")
let subject = $(".TextProject")
let fassign = $(".assignForm")
let statusForm = $(".statusForm")
let bassign = $(".idAssign")
let iassign = $(".Iassign")
let assign = $(".assign")
let pid = $(".pid")
let pid2 = $(".pid2")
let pid3 = $(".pid3")
let pid4 = $(".pid4")


let edit = $(".edit")
let cstatus = $(".cstatus")
let saveStatus = $(".saveStatus")
let descfile = $(".descfile")
let solfile = $(".solfile")

let solutionForm = $(".solutionForm")
let approvalForm = $(".approvalForm")

let addProject = document.getElementById("addProject")
addProject.href = "createStep.html?id="+ new URLSearchParams(window.location.search).get("id")+"&prev=P"//inform createStep that prev page is projects
let btnAddProject = document.getElementById("btnAddProject")


//modal for assigned users 
let modal = document.getElementById("myModal");
let modal_btn = $(".modalBtn");
let delete_btn =$(".deleteStep")
let span = document.getElementsByClassName("close")[0];




let lengthList = null;
let i  = 0;

let sPage = document.querySelector("#switchPage")

let pages = document.querySelectorAll(".page")







window.onload = function () {  setUpButtons(), initLists() }

function setUpButtons(){
  edit.forEach((button, i) => {
    button.addEventListener("click", () => {
       cstatus[i].classList.toggle("display_inline");
       saveStatus[i].classList.toggle("display_inline");
    });
  });



  bassign.forEach((button, i) => {
    button.addEventListener("click", () => {
        iassign[i].classList.toggle("display_block");
        assign[i].classList.toggle("display_block");
    });
  });

  fassign.forEach((button)=> {
    button.addEventListener("submit", async function (e){
      e.preventDefault();
      const formData = new FormData(this);
      try {
        const response = await fetch('/php/assignMember.php', {
            method: 'POST',
            body: formData 
        });

        const data = await response.json();


        alert(data.message);

    } catch (error) {
        console.error("Fetch failed:", error);
        alert("System error.");
    }
    })
  })

  approvalForm.forEach((button)=> {
    button.addEventListener("submit", async function (e){
      e.preventDefault();
      const formData = new FormData(this);
      try {
        const response = await fetch('/php/approveStep.php', {
            method: 'POST',
            body: formData 
        });

        const data = await response.json();

        if (!data.success) {
            alert(data.message);
        }else{
          initLists();
        }
    } catch (error) {
        console.error("Approval failed:", error);
        alert("System error.");
    }
    })
  })

  solutionForm.forEach((button)=> {
    button.addEventListener("submit", async function (e){
      e.preventDefault();
      const formData = new FormData(this);
      try {
        const response = await fetch('/php/uploadSolutionFile.php', {
            method: 'POST',
            body: formData 
        });

        const data = await response.json();
        if(!data.success){
          alert(data.message);

        }else{
          initLists();
        }

    } catch (error) {
        console.error("Approval failed:", error);
        alert("System error.");
    }
    })
  })


  statusForm.forEach((button)=> {
    button.addEventListener("submit", async function (e){
      e.preventDefault();
      const formData = new FormData(this);
      try {
        const response = await fetch('/php/changeStatus.php', {
            method: 'POST',
            body: formData 
        });

        const data = await response.json();
        if(data.success){
          alert(data.message);
          initLists();
        }else{
          alert(data.message);
        }
        

    } catch (error) {
        console.error("Approval failed:", error);
        alert("System error.");
    }
    })
  })


  delete_btn.forEach((button,i) =>{
        const urlParams = new URLSearchParams(window.location.search);
        const list_id = urlParams.get('id')


        button.addEventListener("click",()=>{
        fetch('/php/delete_step.php?lid='+list_id+'&pid=' + delete_btn[i].dataset.id).
        then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        }).then(data =>{
          if(data.success){
              alert("Step deleted.");
              window.location.href = '/html/user/projects/projects.html?id='+list_id+'&page='+currentPage;
          } else {
              alert("Uknown error.");
          }

      })
    .catch(error => {
        alert("Error with server, try again later.");
        console.log(error)
    });
})
})

modal_btn.forEach((button,i) =>{
        button.addEventListener("click",()=>{
        modal.style.display = "block";
        fetch('/php/get_assigned_members_of_step.php?id=' + modal_btn[i].dataset.id)
              .then(response => {
                  if (!response.ok) {
                      throw new Error('Network response was not ok');
                  }
                  return response.json(); 
              })
              .then(data => {         
                  document.getElementById('membersP').textContent = data;
              })
              .catch(error => {
                  console.error('error:', error);
              });
      })
})


}






  


span.onclick = function() {
  modal.style.display = "none";
}





async function initLists(){
  startPage()

    
    let ind = (currentPage - 1) * 6 //index for shown projects
    let check = false;//check for  visible addStep button once
    try {
      const params = new URLSearchParams(window.location.search);
      const lidQ = params.get("id");
      const response = await fetch("/php/initSteps.php?id=" + encodeURIComponent(lidQ));
      if (!response.ok) throw new Error("Server error: " + response.status);

      const data = await response.json();
      if(!data.success){
          alert(data.message); 
          window.location.href = data.redirect;
      }
      
     lengthList = data.titles.length
     if (lengthList > 6){
          if(lengthList <= 12){
              document.getElementById("page3").style.opacity = 0.1;
          }
      }else{
           document.getElementById("page3").style.opacity = 0.1;
          document.getElementById("page2").style.opacity = 0.1;
      }


      for ( i = 0; i<6; i++){
          pid[i].value = data.ids[i+ind]
          pid2[i].value = data.ids[i+ind]
          pid3[i].value = data.ids[i+ind]
          pid4[i].value = data.ids[i+ind]
          modal_btn[i].dataset.id = data.ids[i+ind]
          delete_btn[i].dataset.id = data.ids[i+ind]
          list[i].id = data.ids[i+ind]



          if(data.description_files[i+ind]!=null){
            descfile[i].href = `/php/downloadDescFile.php?pid=${data.ids[i+ind]}&name=${data.description_files[i+ind]}`;
            descfile[i].textContent = "Download file"

          }else{
            descfile[i].textContent = "No file"
          }

          if(data.solution_files[i+ind] != null){
              solfile[i].href = `/php/downloadSolFile.php?pid=${data.ids[i+ind]}&name=${data.solution_files[i+ind]}`;
              solfile[i].textContent = "Download file"
          }else{
            solfile[i].textContent = "No file"
          }
          

          if (data.titles[i + ind] !== undefined ){
              list[i].classList.remove("display_none")
              htitle[i].textContent = data.titles[i + ind];
              hstatus[i].textContent = "Status: "+data.statuses[i + ind]
              console.log(data.subjects[i + ind])
              if(data.subjects[i + ind] != ""){
                subject[i].textContent = data.subjects[i + ind]
              }else{
                subject[i].textContent = "No description."
              }
              
              
              
              if ((data.creator !== data.user)){  
                
                if(data.statuses[i+ind]!="Completed"){
                    solutionForm[i].classList.add("display_block")
                }else{
                  solutionForm[i].classList.remove("display_block")
                  solutionForm[i].classList.add("display_none")
                }
                if(data.approved[i+ind] != 1){
                  edit[i].classList.add("display_block")
                }
              }else{

                if(!check){
                  btnAddProject.classList.add("display_block")
                  check = true;
                }
                bassign[i].classList.add("display_block")
                
                delete_btn[i].classList.add("display_block");

                if(data.statuses[i+ind] == "Completed" && data.approved[i+ind] != 1){
                    approvalForm[i].classList.add("display_block");
                }
                
                if(data.approved[i+ind] == 1){
                  approvalForm[i].classList.remove("display_block");
                }

                
                
              }

            

              if(data.statuses[i+ind] == "Completed" && data.approved[i+ind] != 1){
                  hstatus[i].textContent = "Completed - waiting for approve by creator"
              }else if(data.statuses[i+ind] == "In progress" && data.approved[i+ind] == 0){
                    hstatus[i].textContent = "In progress - Completion not accetped"
                    approvalForm[i].classList.remove("display_block");
              }

            

          }else{
              list[i].classList.add("display_none")
          }


      }
    }catch(error){
        console.error("Could not load projects:", error);
    }

    if (window.location.hash) {
      let hash = window.location.hash.substring(1); 
      let targetElement = document.getElementById(hash);
      
      if (targetElement) {
          targetElement.scrollIntoView({ behavior: 'instant' });
      }
    }
}



 
//PAGES

let currentPage = parseInt(new URLSearchParams(window.location.search).get('page'))

let page1 = document.getElementById("page1")
let page2 = document.getElementById("page2")
let page3 = document.getElementById("page3")



function startPage() {  
    pages[(currentPage > 3)?2:(currentPage - 1)].style.backgroundColor = "#007bff";

    const newParams = new URLSearchParams(window.location.search);
    newParams.set('page', currentPage); 

    window.history.pushState({}, '', `${window.location.pathname}?${newParams.toString()}${window.location.hash}`);
}



function pageOne() {
  if (    pages[1].style.backgroundColor == "rgb(0, 123, 255)"){
    currentPage = currentPage - 1
  }else if (pages[2].style.backgroundColor == "rgb(0, 123, 255)"){
    currentPage = currentPage - 2
  }

  pages[0].style.backgroundColor = "#007bff"
  pages[1].style.backgroundColor = "#1e1e1e"
  pages[2].style.backgroundColor = "#1e1e1e"
  initLists()
  window.scrollTo(0, 0);
  }
  
function pageTwo() {
  if(lengthList <= 6){
        alert("Page 2 does not exist.")
    }else{
      if (    pages[2].style.backgroundColor == "rgb(0, 123, 255)"){
        currentPage = currentPage - 1
      }else if ( pages[0].style.backgroundColor == "rgb(0, 123, 255)"){
        currentPage = currentPage + 1
      }
    pages[0].style.backgroundColor = "#1e1e1e"
    pages[1].style.backgroundColor = "#007bff"
    pages[2].style.backgroundColor = "#1e1e1e"
    initLists()
    window.scrollTo(0, 0);
  }
  
}
  
  
function pageThree() {
    if(lengthList <= 12){
        alert("Page 3 does not exist.")
    }else{
      if (    pages[1].style.backgroundColor == "rgb(0, 123, 255)"){
        currentPage = currentPage + 1
      }else if ( pages[0].style.backgroundColor === "rgb(0, 123, 255)"){
        currentPage = currentPage + 2
      }
        pages[0].style.backgroundColor = "#1e1e1e"
        pages[1].style.backgroundColor = "#1e1e1e"
        pages[2].style.backgroundColor = "#007bff"
        initLists()
        window.scrollTo(0, 0);
   }
}
  
  
  function previousPage() {
    if (currentPage > 1) {
      currentPage--
      pages.forEach((page, i) => {
        if (currentPage - 1 == i) {
          page.style.backgroundColor = "#007bff"
        } else (
          page.style.backgroundColor = "#1e1e1e"
        )
      })
      if(currentPage >= 3){
        page1.textContent = `&ensp;&ensp;&ensp;${currentPage - 2 }&ensp;&ensp;&ensp;`
        page2.textContent = `&ensp;&ensp;&ensp;${currentPage - 1 }&ensp;&ensp;&ensp;`
        page3.textContent = `&ensp;&ensp;&ensp;${currentPage}&ensp;&ensp;&ensp;`
      }
      
      initLists()
      window.scrollTo(0, 0);
      
    } else {
      alert("You are on the first page.")
    }
  }    

  
  function nextPage() {
      if(lengthList <= 6 * currentPage){
        alert("You are on the last page.")
      }else{ 

        currentPage++
        pages.forEach((page, i) => {
            if (( (currentPage > 3)?2:currentPage -1 )  == i) {
                page.style.backgroundColor = "#007bff"
            } else (
                page.style.backgroundColor = "#1e1e1e"
            )
        })



        if(currentPage >= 3){
            page1.textContent = `&ensp;&ensp;&ensp;${currentPage - 2 }&ensp;&ensp;&ensp;`
            page2.textContent = `&ensp;&ensp;&ensp;${currentPage - 1 }&ensp;&ensp;&ensp;`
            page3.textContent = `&ensp;&ensp;&ensp;${currentPage}&ensp;&ensp;&ensp;`
        }

        initLists()
        window.scrollTo(0, 0);
    }
  }
  

