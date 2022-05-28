function processTextArea(){

    let textArea = document.getElementById('message');
    let message = textArea.value;
    
    for (let i = 0; i < message.length; i++){
        let start = i;
        for ( ; i < message.length && i - start < 70; i++)
            if (message.charAt(i) == "\n")
                break;
        
        if (i - start == 70)
            message = message.slice(0, i - 1) + "\n" + message.slice(i);


    }

    textArea.value = message;
}

function processSubmit(){
    let textArea = document.getElementById('message'); 
    let message = textArea.value;
    message.replace(new RegExp('\r\n'), '\n');
    let res = [];

    while (message.length > 0){

        let i = 0;
        let isNewLine = false;
        for ( ; i < 70 && isNewLine; )
            if ( message.charAt(i) == "\n" )
                isNewLine = true;
            else 
                i++;

        i = isNewLine ? i : i - 1; 
        res.push(message.slice(0, i));
        message = message.slice(i + 1);

    }

    message = '';
    for (let i = 0; i < res.length; i++){
        res[i].replace("\n", "");
        res[i] = res[i] + "\r\n";
        message += res[i];
    }

    textArea.value = message;
}