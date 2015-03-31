function stoppedTyping(){
        if(this.value.length > 0) { 
            document.getElementById('updateButton').disabled = false; 
        } else { 
            document.getElementById('updateButton').disabled = true;
        }
    }

/*
function verify(){
	if myText is empty{
		alert "Put some text in there!"
		return
	}
	else{
		do button functionality
	}
}
* **/
