const ACCESS_TOKEN = "figd_qHfBkeqP2rXClHNyo33vSe5w0MoXwbF4ufyflH-w";
const FILE_ID = "8el5EkPAzbi72joapxJ2RE";
const NODE_ID = "2:7";
const FORMAT = 'svg';


window.onload = ()=>{

        var myHeaders = new Headers();
        myHeaders.append("x-figma-token", "figd_qHfBkeqP2rXClHNyo33vSe5w0MoXwbF4ufyflH-w");
        
        var requestOptions = {
          method: 'GET',
          headers: myHeaders,
          redirect: 'follow'
        };
        
        fetch(`https://api.figma.com/v1/images/${FILE_ID}?ids=${NODE_ID}&format=${FORMAT}`, requestOptions)
          .then(response => response.json())
          .then(result => {
            console.log( result)
            document.querySelector(".figma").src = result.images[NODE_ID];
          })
          .catch(error => console.log('error', error));
}