//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);    
    
        if (src){
            switch (posCaption){
                case 'T': captionTop    = ans.proposition + qbr ; break;
                case 'B': captionBottom = qbr + ans.proposition ; break;
                default: break;
            }
            tHtmlmg.push(`
                <div id="${ans.ansId}-divImg2" class='imagesDaDSortItems_myimg2' 
                <img id="${ans.ansId}-img2"  src="${src}" title="${ans.image2}" ${ImgStyle} alt="" >
                ${captionBottom}</div>`);
        }else{
            this.data.imagesRefExists = false
            break;
        }

