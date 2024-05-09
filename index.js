document.addEventListener("DOMContentLoaded", main);
    function main() {

        document.getElementById("color").addEventListener("click", oscuro);
        
    }

    function oscuro() {
        let color = window.getComputedStyle( document.getElementById("color")).getPropertyValue("background-color");

        if (color == "rgb(0, 0, 0)") {
            document.getElementById("color").style.backgroundColor = 'red';
                document.getElementById("color").style.color = 'white';
                document.getElementById("color").style.fontSize = '32px';
        } else {
            document.getElementById("color").style.backgroundColor = 'black';
                document.getElementById("color").style.color = 'white';
                document.getElementById("color").style.fontSize = '9px';
        }
                
        
    }