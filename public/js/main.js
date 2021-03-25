//attente de l'affichage complet de la page 
document.addEventListener('DOMContentLoaded', () => {

        //fonction pour la lecture automatique et le passage des images du caroussele
        $('.post-wrapper').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 2000,
          nextArrow:$('.next'),
          prevArrow:$('.prev')
            
        }); 
        
        //selector des differents element dans le HTML pour l'affichage/ou non affichage de la modal
        let overlay =document.getElementById('overlay'); 
        let modal =document.getElementById('modal'); 
        let open =document.getElementById('open'); 
        let close=document.getElementById('close');  
        
        //ajout un evenement au click sur l'element HTML open (div)
        //lors du click il ajout aux elements HTML overlay et modal la classe visible qui incremente l'opasité de l'element
        open.addEventListener('click',function (e){     
          overlay.classList.add('visible');     
          modal.classList.add('visible');
        });
        
        //ajout un evenement au click sur l'element HTML open (div)
        //lors du click il supprime aux elements HTML overlay et modal la classe visible qui diminue l'opasité de l'element
        close.addEventListener('click', function (e){    
          overlay.classList.remove('visible');     
          modal.classList.remove('visible');
        });     
        
        }); 
        
        //selector l'element dans le HTML pour le groupage/ou non des liens dans la navbar
        let icon= document.getElementById('icon');
        //ajout un evenement au click sur l'element HTML icon (i)
        icon.addEventListener('click', function (e){
            
              var x = document.getElementById("navBar");
              
              //lors du click sur icon s'il a comme classe que navBar il lui rajoute responsive  
              if (x.className === "navBar"){
                
                x.className += " responsive";
              
              //si non il remplace les classes par navBar 
              } else {
                
                x.className = "navBar";
              
              } 

          
        });