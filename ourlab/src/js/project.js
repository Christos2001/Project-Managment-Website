function $(x){return document.querySelectorAll(x)}


let list = $('.project')

let htitle = $(".title")
let hcreators = $(".creator")
let hdates = $(".date")


let i  = 0;

let sPage = document.querySelector("#switchPage")

let pages = document.querySelectorAll(".page")



let btnAddProject = $(".btnAddProject") 
let addProject = $(".addProject")
let remProject = $(".remProject")
let fremProject = $(".fremPro")
let hidRem = $(".hidRem")
let enterList = $(".enterList")

let currentPage = 1








    
window.onload = function () {  initLists();}


let lengthList  = null





async function initLists(){
    pages[(currentPage > 3)?2:(currentPage - 1)].style.backgroundColor = "#007bff"
    let ind = (currentPage - 1) * 6 //index for shown projects
  
      try {
        const params = new URLSearchParams(window.location.search);
        const search = params.get("searchT");
        let phpINIT = null
        if(search && search != "all"){
            phpINIT = "/php/searchProject.php?searchT=" + encodeURIComponent(search);
        }else{
            phpINIT = '/php/initMyProjects.php';
        }
        const response = await fetch(phpINIT);
          
          
          if (!response.ok) throw new Error("Server error: " + response.status);

          const data = await response.json();
          if(!data.success){
            alert(data.message); 
            window.location.href = data.redirect;
        }
      
          //for pagination
          lengthList = data.titles.length;
          if (lengthList > 6){
              if(lengthList <= 12){
                  document.getElementById("page3").style.opacity = 0.1;
              }
          }else{
              document.getElementById("page2").style.opacity = 0.1;
              document.getElementById("page3").style.opacity = 0.1;
          }
          //initialization
          for ( i = 0; i<6; i++){
              if (data.titles[i + ind] !== undefined){
                  htitle[i].textContent ="Project title:   "+ data.titles[i + ind];
                  hcreators[i].textContent = "Creator:   "+data.creators[i + ind]
                  hdates[i].textContent ="Date:   "+ data.dates[i + ind]
                  if (data.creators[i + ind] == data.user){
                      addProject[i].href = `createStep.html?id=${data.lIDs[i+ind]}&prev=PL`//inform createStep that prev page was Project List page
                  }else{
                    btnAddProject[i].style.visibility = "hidden"
                    btnAddProject[i].textContent = ""
                    remProject[i].style.visibility = "hidden"
                  }
                  enterList[i].href = `projects.html?id=${data.lIDs[i+ind]}&page=1`;


                  list[i].style.visibility = "visible"
                  

                }else{
                    list[i].style.visibility = "hidden"
                }

              remProject.forEach((b,i) => {
                let ind = (currentPage - 1) * 6
                hidRem[i].value = data.lIDs[ind + i]
            })


          }
      } catch (error) {
          alert("Uknown error.")
          console.error("Could not load projects:", error);
      }
    
    

}
 
//PAGES
let page1 = document.getElementById("page1")
let page2 = document.getElementById("page2")
let page3 = document.getElementById("page3")



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
    }
  }
  

