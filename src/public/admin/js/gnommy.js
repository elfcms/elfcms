class Gnommy {

    textfield
    textfieldbox
    editorbox
    editorfield
    buttonbox
    footerbox
    pEnter = false
    showHtml = false
    noResize = false

    constructor (textfield, params={}) {
        if (params.noResize) {
            this.noResize = true
        }
        if (params.pEnter) {
            this.pEnter = true
        }
        if (params.showHtml) {
            this.showHtml = true
        }
        if (params.height) {
            this.fieldHeight = parseInt(params.height)
        }
        this.textfield = this.checkTextarea(textfield)
        this.textfield.style = false
        this.init()
    }

    checkTextarea (textfield) {
        if (typeof textfield == 'string') {
            textfield = document.querySelector(textfield)
        }
        if (textfield && textfield instanceof HTMLElement) {
            if (textfield.tagName.toLowerCase() !== 'textarea') {
                let textarea = document.createElement('textarea');
                textarea.setAttribute('name',textfield.getAttribute('name'))
                textarea.setAttribute('id',textfield.getAttribute('id'))
                textfield = textarea
            }
            return textfield
        }
        return false
    }

    createButton () {
        let button = document.createElement('button')
        button.classList.add('gnommy-editor-button')
        return button
    }

    fragmentWrap (wrapper,params=null,empty=false) {
        let selection = null
        selection = document.getSelection();
        if (selection && selection.rangeCount > 0) {
            let selectionRange = selection.getRangeAt(0)

            if ((selection.anchorNode.parentNode.closest('.gnommy-editor-field') || selection.anchorNode.parentNode.classList.contains('gnommy-editor-field')) && selection.anchorNode.parentNode.closest('.gnommy-editor-box') === this.editorbox) {

                if(selection.focusOffset != selection.anchorOffset) {
                    
                    let start, end
                    if (selection.anchorOffset < selection.focusOffset) {
                        start = selection.anchorOffset
                        end = selection.focusOffset
                    }
                    else {
                        start = selection.focusOffset
                        end = selection.anchorOffset
                    }
                    let startNode, endNode;
                    if (selectionRange.startOffset == selection.anchorOffset) {
                        startNode = selection.anchorNode.parentNode ?? selection.anchorNode
                        endNode = selection.focusNode.parentNode ?? selection.focusNode
                    }
                    else {
                        startNode = selection.focusNode.parentNode ?? selection.focusNode
                        endNode = selection.anchorNode.parentNode ?? selection.anchorNode
                    }
                    if (wrapper.toUpperCase() == selection.focusNode.parentNode.nodeName || (wrapper.nodeName && wrapper.nodeName == selection.focusNode.parentNode.nodeName) || wrapper.toLowerCase() == selection.anchorNode.parentNode.nodeName || (wrapper.nodeName && wrapper.nodeName == selection.anchorNode.parentNode.nodeName)) {

                        selectionRange.setStartBefore(startNode)
                        selectionRange.setEndAfter(endNode)
                        let fragment = selectionRange.extractContents()
                        selectionRange.insertNode(this.unwrap(fragment.childNodes,wrapper))
                    }
                    else {
                        let fragment = selectionRange.extractContents()
                        selectionRange.insertNode(this.wrap(fragment,wrapper,params))
                    }
                    selectionRange.collapse()
                }
                else if (empty) {
                    let element = document.createElement(wrapper),
                        br = document.createElement('br')
                    element.append(br)
                    selectionRange.insertNode(element)
                    selectionRange.setEndAfter(br)
                    selectionRange.setStartAfter(br)
                }
            }
        }
    }

    editImagePopup (img) {
        let self = this,
            imgWidth = null,
            imgHeight = null,
            ratio = 1,
            naturalWidth = null,
            naturalHeight = null,
            naturalRatio = 1
             
        imgWidth = img.width;
        imgHeight = img.height;
        if (imgWidth && imgHeight) {
            ratio = imgWidth/imgHeight
        }
        naturalWidth = img.naturalWidth;
        naturalHeight = img.naturalHeight;
        if (naturalWidth && naturalHeight) {
            naturalRatio = naturalWidth/naturalHeight
        }

        if (!imgWidth && naturalWidth) {
            imgWidth = naturalWidth
        }
        if (!imgHeight && naturalHeight) {
            imgHeight = naturalHeight
        }

        this.imgeditbox = document.createElement('div')
        this.imgeditbox.classList.add('gnommy-editor-imgedit-box')
        this.imgeditTitle = document.createElement('h4')
        this.imgeditTitle.innerHTML = 'Edit image'
        this.imgeditFieldLabel = document.createElement('label')
        this.imgeditFieldLabel.setAttribute('for','imagesrc')
        this.imgeditFieldLabel.innerHTML = 'Image source'
        this.imgeditField = document.createElement('input')
        this.imgeditField.type = 'text'
        this.imgeditField.name = 'imagesrc'
        this.imgeditField.id = 'imagesrc'
        let imgSrc = img.getAttribute('src')
        this.imgeditField.value = imgSrc
        
        this.imgeditTextLabel = document.createElement('label')
        this.imgeditTextLabel = document.createElement('label')
        this.imgeditTextLabel.setAttribute('for','imagesrc')
        this.imgeditTextLabel.innerHTML = 'Alternate text'
        this.imgeditText = document.createElement('input')
        this.imgeditText.type = 'text'
        this.imgeditText.name = 'imagealt'
        this.imgeditText.id = 'imagealt'
        this.imgeditText.value = img.getAttribute('alt')

        this.imgeditField.addEventListener('change',function(){
            let oldNaturalRatio = naturalRatio
            if (!img || this.value != imgSrc) {
                img.src = this.value
                img.onload = function() {
                    naturalWidth = this.naturalWidth;
                    naturalHeight = this.naturalHeight;
                    self.imgeditSizeWidth.value = naturalWidth
                    self.imgeditSizeHeight.value = naturalHeight
                    if (naturalWidth && naturalHeight) {
                        naturalRatio = naturalWidth/naturalHeight
                    }
                    else {
                        naturalRatio = oldNaturalRatio
                    }
                }
                img.onerror = function() {
                    naturalWidth = null;
                    naturalHeight = null;
                    self.imgeditSizeWidth.value = naturalWidth
                    self.imgeditSizeHeight.value = naturalHeight
                    naturalRatio = oldNaturalRatio
                }
            }
        })

        /* Size */
        this.imgeditSizeBox = document.createElement('div')
        this.imgeditSizeBox.classList.add('gnommy-editor-imagepopup-size')
        
        this.imgeditSizeWidthLabel = document.createElement('label')
        this.imgeditSizeWidthLabel.setAttribute('for','imagewidth')
        this.imgeditSizeWidthLabel.innerHTML = 'Width'
        this.imgeditSizeWidth = document.createElement('input')
        this.imgeditSizeWidth.type = 'number'
        this.imgeditSizeWidth.name = 'imagewidth'
        this.imgeditSizeWidth.id = 'imagewidth'
        this.imgeditSizeWidth.value = imgWidth

        this.imgeditSizeHeightLabel = document.createElement('label')
        this.imgeditSizeHeightLabel.setAttribute('for','imageheight')
        this.imgeditSizeHeightLabel.innerHTML = 'Height'
        this.imgeditSizeHeight = document.createElement('input')
        this.imgeditSizeHeight.type = 'number'
        this.imgeditSizeHeight.name = 'imageheight'
        this.imgeditSizeHeight.id = 'imageheight'
        this.imgeditSizeHeight.value = imgHeight

        this.imgeditAspectRatioLabel = document.createElement('label')
        this.imgeditAspectRatioLabel.setAttribute('for','imageratio')
        this.imgeditAspectRatioLabel.innerHTML = 'Keep aspect ratio'
        this.imgeditAspectRatio = document.createElement('input')
        this.imgeditAspectRatio.type = 'checkbox'
        this.imgeditAspectRatio.name = 'imageratio'
        this.imgeditAspectRatio.id = 'imageratio'
        this.imgeditAspectRatio.checked = Math.round(naturalRatio * 10) == Math.round(ratio * 10)

        this.imgeditSizeBox.append(this.imgeditSizeWidthLabel,this.imgeditSizeWidth,this.imgeditSizeHeightLabel,this.imgeditSizeHeight,this.imgeditAspectRatio,this.imgeditAspectRatioLabel)

        this.imgeditSizeWidth.addEventListener('input',function(){
            imgHeightChange(this.value)
        })
        this.imgeditSizeWidth.addEventListener('change',function(){
            imgHeightChange(this.value)
        })
        this.imgeditSizeHeight.addEventListener('input',function(){
            imgWidthChange(this.value)
        })
        this.imgeditSizeHeight.addEventListener('change',function(){
            imgWidthChange(this.value)
        })

        function imgHeightChange(width) {
            width = parseInt(width)
            if (self.imgeditAspectRatio.checked && width && width > 0) {
                imgHeight =  Math.round(width/naturalRatio)
                self.imgeditSizeHeight.value = imgHeight
            }
        }

        function imgWidthChange(height) {
            height = parseInt(height)
            if (self.imgeditAspectRatio.checked && height && height > 0) {
                imgWidth =  Math.round(height*naturalRatio)
                self.imgeditSizeWidth.value = imgWidth
            }
        }

        /* /Size */

        this.imgeditButtonBox = document.createElement('div')
        this.imgeditButtonBox.classList.add('gnommy-editor-imagepopup-buttons')
        this.imgeditOkButton = document.createElement('button')
        this.imgeditOkButton.innerHTML = 'OK'
        this.imgeditCancelButton = document.createElement('button')
        this.imgeditCancelButton.innerHTML = 'Cancel'
        this.imgeditButtonBox.append(this.imgeditOkButton,this.imgeditCancelButton)
        this.imgeditbox.append(this.imgeditTitle,this.imgeditFieldLabel,this.imgeditField,this.imgeditTextLabel,this.imgeditText,this.imgeditSizeBox,this.imgeditButtonBox)

        this.imgeditCancelButton.addEventListener('click',function(){
            self.closePopup()
        })

        this.imgeditOkButton.addEventListener('click',function(){
            if (!img || !self.imgeditField.value) {
                return false
            }
            img.src = self.imgeditField.value
            if (self.imgeditText.value.length) {
                img.alt = self.imgeditText.value
            }
            else {
                img.removeAttribute('alt')
            }
            if (self.imgeditSizeWidth.value.length) {
                img.width = self.imgeditSizeWidth.value
            }
            else {
                img.removeAttribute('width')
            }
            if (self.imgeditSizeHeight.value.length) {
                img.height = self.imgeditSizeHeight.value
            }
            else {
                img.removeAttribute('height')
            }
            self.closePopup()
        })

        return this.imgeditbox
    }

    createImagePopup (src=null,img=null) {
        let self = this,
            imgWidth = null,
            imgHeight = null,
            ratio = 1,
            isImg = false
        if (!img) {
            img = new Image()
            if (src) {
                img.src = src
            }
        }
        else if (img) {
            src = img.src
        }
        img.onerror = function() {
            isImg = false
        }
        img.onload = function() {      
            imgWidth = this.width;
            imgHeight = this.height;
            if (imgWidth && imgHeight) {
                ratio = imgWidth/imgHeight
            }
            isImg = true
        }

        this.imgeditbox = document.createElement('div')
        this.imgeditbox.classList.add('gnommy-editor-imgedit-box')
        this.imgeditTitle = document.createElement('h4')
        this.imgeditTitle.innerHTML = 'Add image'
        this.imgeditFieldLabel = document.createElement('label')
        this.imgeditFieldLabel.setAttribute('for','imagesrc')
        this.imgeditFieldLabel.innerHTML = 'Image source'
        this.imgeditField = document.createElement('input')
        this.imgeditField.type = 'text'
        this.imgeditField.name = 'imagesrc'
        this.imgeditField.id = 'imagesrc'
        this.imgeditField.value = src
        
        this.imgeditTextLabel = document.createElement('label')
        this.imgeditTextLabel = document.createElement('label')
        this.imgeditTextLabel.setAttribute('for','imagesrc')
        this.imgeditTextLabel.innerHTML = 'Alternate text'
        this.imgeditText = document.createElement('input')
        this.imgeditText.type = 'text'
        this.imgeditText.name = 'imagealt'
        this.imgeditText.id = 'imagealt'

        this.imgeditField.addEventListener('change',function(){
            img.src = this.value
            img.onload = function() {
                imgWidth = this.width;
                imgHeight = this.height;
                self.imgeditSizeWidth.value = imgWidth
                self.imgeditSizeHeight.value = imgHeight
                if (imgWidth && imgHeight) {
                    ratio = imgWidth/imgHeight
                }
                else {
                    ratio = 1
                }
                isImg = true
            }
            img.onerror = function() {
                imgWidth = null;
                imgHeight = null;
                self.imgeditSizeWidth.value = imgWidth
                self.imgeditSizeHeight.value = imgHeight
                ratio = 1
                isImg = false
            }
        })

        /* Size */
        this.imgeditSizeBox = document.createElement('div')
        this.imgeditSizeBox.classList.add('gnommy-editor-imagepopup-size')
        
        this.imgeditSizeWidthLabel = document.createElement('label')
        this.imgeditSizeWidthLabel.setAttribute('for','imagewidth')
        this.imgeditSizeWidthLabel.innerHTML = 'Width'
        this.imgeditSizeWidth = document.createElement('input')
        this.imgeditSizeWidth.type = 'number'
        this.imgeditSizeWidth.name = 'imagewidth'
        this.imgeditSizeWidth.id = 'imagewidth'
        this.imgeditSizeWidth.value = imgWidth

        this.imgeditSizeHeightLabel = document.createElement('label')
        this.imgeditSizeHeightLabel.setAttribute('for','imageheight')
        this.imgeditSizeHeightLabel.innerHTML = 'Height'
        this.imgeditSizeHeight = document.createElement('input')
        this.imgeditSizeHeight.type = 'number'
        this.imgeditSizeHeight.name = 'imageheight'
        this.imgeditSizeHeight.id = 'imageheight'
        this.imgeditSizeHeight.value = imgHeight

        this.imgeditAspectRatioLabel = document.createElement('label')
        this.imgeditAspectRatioLabel.setAttribute('for','imageratio')
        this.imgeditAspectRatioLabel.innerHTML = 'Keep aspect ratio'
        this.imgeditAspectRatio = document.createElement('input')
        this.imgeditAspectRatio.type = 'checkbox'
        this.imgeditAspectRatio.name = 'imageratio'
        this.imgeditAspectRatio.id = 'imageratio'
        this.imgeditAspectRatio.checked = true

        this.imgeditSizeBox.append(this.imgeditSizeWidthLabel,this.imgeditSizeWidth,this.imgeditSizeHeightLabel,this.imgeditSizeHeight,this.imgeditAspectRatio,this.imgeditAspectRatioLabel)

        this.imgeditSizeWidth.addEventListener('input',function(){
            imgHeightChange(this.value)
        })
        this.imgeditSizeWidth.addEventListener('change',function(){
            imgHeightChange(this.value)
        })
        this.imgeditSizeHeight.addEventListener('input',function(){
            imgWidthChange(this.value)
        })
        this.imgeditSizeHeight.addEventListener('change',function(){
            imgWidthChange(this.value)
        })

        function imgHeightChange(width) {
            width = parseInt(width)
            if (self.imgeditAspectRatio.checked && width && width > 0) {
                imgHeight =  Math.round(width/ratio)
                self.imgeditSizeHeight.value = imgHeight
            }
        }

        function imgWidthChange(height) {
            height = parseInt(height)
            if (self.imgeditAspectRatio.checked && height && height > 0) {
                imgWidth =  Math.round(height*ratio)
                self.imgeditSizeWidth.value = imgWidth
            }
        }

        /* /Size */

        this.imgeditButtonBox = document.createElement('div')
        this.imgeditButtonBox.classList.add('gnommy-editor-imagepopup-buttons')
        this.imgeditOkButton = document.createElement('button')
        this.imgeditOkButton.innerHTML = 'OK'
        this.imgeditCancelButton = document.createElement('button')
        this.imgeditCancelButton.innerHTML = 'Cancel'
        this.imgeditButtonBox.append(this.imgeditOkButton,this.imgeditCancelButton)
        this.imgeditbox.append(this.imgeditTitle,this.imgeditFieldLabel,this.imgeditField,this.imgeditTextLabel,this.imgeditText,this.imgeditSizeBox,this.imgeditButtonBox)

        this.imgeditCancelButton.addEventListener('click',function(){
            self.closePopup()
        })

        this.imgeditOkButton.addEventListener('click',function(){
            if (!isImg) {
                return false
            }
            self.setImage (img)
        })

        return this.imgeditbox
    }

    setImage (img) {
        let selection = document.getSelection();
        if (!selection || !selection.anchorNode || !this.imgeditField.value || this.imgeditField.value.length == 0) {
            return false
        }

        if (selection && selection.rangeCount > 0) {
            let selectionRange = selection.getRangeAt(0)
            
            if (this.lastRange.container && this.lastRange.container.nodeName == 'IMG') {
                this.lastRange.container.src = this.imgeditField.value
                this.lastRange.container.setAttribute('alt',this.imgeditText.value)
                if (this.imgeditSizeWidth.value && this.imgeditSizeWidth.value.length > 0) {
                    this.lastRange.container.setAttribute('width',this.imgeditSizeWidth.value)
                }
                if (this.imgeditSizeHeight.value && this.imgeditSizeHeight.value.length > 0) {
                    this.lastRange.container.setAttribute('height',this.imgeditSizeHeight.value)
                }
                selectionRange.setEndAfter(this.lastRange.container)
                selectionRange.setStartAfter(this.lastRange.container)
                this.contentClone(this.editorfield,this.textfield)
            }
            else {
                img.setAttribute('alt',this.imgeditText.value)
                if (this.imgeditSizeWidth.value && this.imgeditSizeWidth.value.length > 0) {
                    img.setAttribute('width',this.imgeditSizeWidth.value)
                }
                if (this.imgeditSizeHeight.value && this.imgeditSizeHeight.value.length > 0) {
                    img.setAttribute('height',this.imgeditSizeHeight.value)
                }

                selectionRange.setStart(this.lastRange.startContainer, this.lastRange.startOffset)
                selectionRange.setEnd(this.lastRange.endContainer, this.lastRange.endOffset)
                selectionRange.deleteContents()
                selectionRange.insertNode(img)
                selectionRange.setEndAfter(img)
                selectionRange.setEndAfter(img)
            }
            
            this.setImageContext(img)
            
            selectionRange.collapse()
        }
        this.closePopup()
    }

    createHeaderPopup () {
        let self = this

        this.heditbox = document.createElement('div')
        this.heditbox.classList.add('gnommy-editor-hedit-box')

        this.heditButtonOne = document.createElement('button')
        this.heditButtonOne.classList.add('gnommy-editor-hedit-button')
        this.heditButtonOne.innerHTML = 'Header 1'
        this.heditButtonOne.addEventListener('click',function(){
            self.fragmentWrap('h1',null,true)
            self.closePopup()
        }) 
        this.heditButtonTwo = document.createElement('button')
        this.heditButtonTwo.classList.add('gnommy-editor-hedit-button')
        this.heditButtonTwo.innerHTML = 'Header 2'
        this.heditButtonTwo.addEventListener('click',function(){
            self.fragmentWrap('h2',null,true)
            self.closePopup()
        }) 
        this.heditButtonThree = document.createElement('button')
        this.heditButtonThree.classList.add('gnommy-editor-hedit-button')
        this.heditButtonThree.innerHTML = 'Header 3'
        this.heditButtonThree.addEventListener('click',function(){
            self.fragmentWrap('h3',null,true)
            self.closePopup()
        }) 
        this.heditButtonFour = document.createElement('button')
        this.heditButtonFour.classList.add('gnommy-editor-hedit-button')
        this.heditButtonFour.innerHTML = 'Header 4'
        this.heditButtonFour.addEventListener('click',function(){
            self.fragmentWrap('h4',null,true)
            self.closePopup()
        }) 
        this.heditButtonFive = document.createElement('button')
        this.heditButtonFive.classList.add('gnommy-editor-hedit-button')
        this.heditButtonFive.innerHTML = 'Header 5'
        this.heditButtonFive.addEventListener('click',function(){
            self.fragmentWrap('h5',null,true)
            self.closePopup()
        }) 
        this.heditButtonSix = document.createElement('button')
        this.heditButtonSix.classList.add('gnommy-editor-hedit-button')
        this.heditButtonSix.innerHTML = 'Header 6'
        this.heditButtonSix.addEventListener('click',function(){
            self.fragmentWrap('h6',null,true)
            self.closePopup()
        }) 

        this.linkeditButtonBox = document.createElement('div')
        this.linkeditButtonBox.classList.add('gnommy-editor-linkpopup-buttons')
        this.linkeditCancelButton = document.createElement('button')
        this.linkeditCancelButton.innerHTML = 'Cancel'
        this.linkeditButtonBox.append(this.linkeditCancelButton)
        this.heditbox.append(this.heditButtonOne,this.heditButtonTwo,this.heditButtonThree,this.heditButtonFour,this.heditButtonFive,this.heditButtonSix,this.linkeditButtonBox)

        this.linkeditCancelButton.addEventListener('click',function(){
            self.closePopup()
        })

        return this.heditbox
    }

    createLinkPopup (text='',link='') {
        let self = this
        if (typeof text == 'string' && text.length > 0 && (!link || link.length == 0)) {
            if (text.slice(0,1) == '/' || text.slice(0,4).toLowerCase() == 'http') {
                link = text
            }
        }
        this.linkeditbox = document.createElement('div')
        this.linkeditbox.classList.add('gnommy-editor-linkedit-box')
        this.linkeditTitle = document.createElement('h4')
        this.linkeditTitle.innerHTML = 'Create link'
        this.linkeditTextLabel = document.createElement('label')
        this.linkeditTextLabel.setAttribute('for','linktext')
        this.linkeditTextLabel.innerHTML = 'Text'
        this.linkeditText = document.createElement('textarea')
        this.linkeditText.name = 'linktext'
        this.linkeditText.id = 'linktext'
        this.linkeditText.value = text
        this.linkeditLinkLabel = document.createElement('label')
        this.linkeditLinkLabel.setAttribute('for','linkvalue')
        this.linkeditLinkLabel.innerHTML = 'Link'
        this.linkeditLink = document.createElement('input')
        this.linkeditLink.type = 'text'
        this.linkeditLink.name = 'linkvalue'
        this.linkeditLink.id = 'linkvalue'
        this.linkeditLink.value = link
        this.linkeditButtonBox = document.createElement('div')
        this.linkeditButtonBox.classList.add('gnommy-editor-linkpopup-buttons')
        this.linkeditOkButton = document.createElement('button')
        this.linkeditOkButton.innerHTML = 'OK'
        this.linkeditCancelButton = document.createElement('button')
        this.linkeditCancelButton.innerHTML = 'Cancel'
        this.linkeditButtonBox.append(this.linkeditOkButton,this.linkeditCancelButton)
        this.linkeditbox.append(this.linkeditTitle,this.linkeditTextLabel,this.linkeditText,this.linkeditLinkLabel,this.linkeditLink,this.linkeditButtonBox)

        this.linkeditCancelButton.addEventListener('click',function(){
            self.closePopup()
        })

        this.linkeditOkButton.addEventListener('click',function(){
            self.setLink (self.linkeditLink.value, self.linkeditText.value)
        })

        return this.linkeditbox
    }

    unsetLink () {
        let selection = null
        selection = document.getSelection();

        if (!selection || !selection.anchorNode) {
            return false
        }

        if ((selection.anchorNode.parentNode.closest('.gnommy-editor-field') || selection.anchorNode.parentNode.classList.contains('gnommy-editor-field')) && selection.anchorNode.parentNode.closest('.gnommy-editor-box') === this.editorbox) {

            if (selection && selection.rangeCount > 0) {
                let selectionRange = selection.getRangeAt(0)
                
                let parent = selectionRange.commonAncestorContainer,
                    firstElement = selectionRange.startContainer,
                    lastElement = selectionRange.endContainer,
                    link = false,
                    fragment
                if (parent.nodeName == 'A') {
                    link = parent
                }
                else {
                    link = this.getParent(parent,'A')
                }
                if (!link) {
                    fragment = selectionRange.extractContents()
                    selectionRange.insertNode(this.unwrap(fragment.childNodes,'a'))
                    selectionRange.collapse()
                }
                else if (link.firstChild === firstElement && link.lastChild !== lastElement) {
                    selectionRange.setStartBefore(link.firstChild)
                    fragment = selectionRange.extractContents()
                    selectionRange.setStartBefore(link)
                    selectionRange.setEndBefore(link)
                    selectionRange.insertNode(fragment)
                }
                else if (link.firstChild !== firstElement && link.lastChild === lastElement) {
                    selectionRange.setStartAfter(link.lastChild)
                    fragment = selectionRange.extractContents()
                    selectionRange.setStartAfter(link)
                    selectionRange.setEndAfter(link)
                    selectionRange.insertNode(fragment)
                }
                else {
                    selectionRange.selectNode(link)
                    fragment = selectionRange.extractContents()
                    selectionRange.insertNode(this.unwrap(fragment.childNodes,'a'))
                    selectionRange.collapse()
                }
            }
        }
    }

    setLink (link, text = '') {
        if (this.isEmpty(text)) {
            text = link
        }
        if (!link || link.length == 0) {
            return false
        }

        let selection = document.getSelection();

        if (selection && selection.rangeCount > 0) {
            let selectionRange = selection.getRangeAt(0)
            
            if (this.lastRange.container && this.lastRange.container.nodeName == 'A') {
                this.lastRange.container.innerHTML = text
                this.lastRange.container.setAttribute('href',link)
                selectionRange.setEndAfter(this.lastRange.container)
                selectionRange.setStartAfter(this.lastRange.container)
                this.contentClone(this.editorfield,this.textfield)
            }
            else {
                let linkElement = document.createElement('a')
                linkElement.setAttribute('href',link)
                linkElement.innerHTML = text

                selectionRange.setStart(this.lastRange.startContainer, this.lastRange.startOffset)
                selectionRange.setEnd(this.lastRange.endContainer, this.lastRange.endOffset)
                selectionRange.deleteContents()
                selectionRange.insertNode(linkElement)
                selectionRange.setEndAfter(linkElement)
                selectionRange.setEndAfter(linkElement)
            }
            selectionRange.collapse()
        }
        this.closePopup()
    }

    getPopupLink () {
        let selection = null
        selection = document.getSelection();
        if (!selection || !selection.anchorNode) {
            return false
        }
        if ((selection.anchorNode.parentNode.closest('.gnommy-editor-field') || selection.anchorNode.parentNode.classList.contains('gnommy-editor-field')) && selection.anchorNode.parentNode.closest('.gnommy-editor-box') === this.editorbox) {

            if (selection && selection.rangeCount > 0) {
                let selectionRange = selection.getRangeAt(0)

                let text = this.elementToString(selectionRange.cloneContents()),
                    container = selectionRange.commonAncestorContainer,
                    link = ''

                if (container.nodeName == 'A') {
                    selectionRange.selectNode(container)
                    text = container.innerHTML
                    link = container.href
                }

                this.openPopup(this.createLinkPopup(text,link),'link')
                this.lastRange = {'startContainer': selectionRange.startContainer, 'startOffset': selectionRange.startOffset, 'endContainer': selectionRange.endContainer, 'endOffset': selectionRange.endOffset, 'container': container}
            }
        }
    }

    getPopupImage () {
        let selection = null
        selection = document.getSelection();
        if (!selection || !selection.anchorNode) {
            return false
        }
        if (selection && selection.rangeCount > 0) {
            let selectionRange = selection.getRangeAt(0)

            let container = selectionRange.commonAncestorContainer,
                src = '',
                img = null

            if (container.nodeName == 'IMG') {
                selectionRange.selectNode(container)
                src  = container.src
                img = container
            }

            this.openPopup(this.createImagePopup(src,img),'image')
            this.lastRange = {'startContainer': selectionRange.startContainer, 'startOffset': selectionRange.startOffset, 'endContainer': selectionRange.endContainer, 'endOffset': selectionRange.endOffset, 'container': container}
        }
    }






    init () {
        let self = this

        this.editorbox = document.createElement('div')
        this.editorbox.classList.add('gnommy-editor-box')

        this.buttonbox = document.createElement('div')
        this.buttonbox.classList.add('gnommy-editor-buttons')

        this.popuplayer = document.createElement('div')
        this.popuplayer.classList.add('gnommy-editor-popup-layer')
        this.popupwindow = document.createElement('div')
        this.popupwindow.classList.add('gnommy-editor-popup-window')
        
        this.popuplayer.append(this.popupwindow)
        this.editorbox.append(this.popuplayer)

        /* buttons */
        this.headerButton = this.createButton()
        this.headerButton.innerHTML = '<b>H</b>'
        this.headerButton.title = 'Header'
        this.headerButton.addEventListener('click',function(){
            self.openPopup(self.createHeaderPopup(),'header')
        })

        this.boldButton = this.createButton()
        this.boldButton.innerHTML = '<b>B</b>'
        this.boldButton.title = 'Bold'
        this.boldButton.addEventListener('click',function(){
            self.fragmentWrap('b')
        })

        this.italicButton = this.createButton()
        this.italicButton.innerHTML = '<i>I</i>'
        this.italicButton.title = 'Italic'
        this.italicButton.addEventListener('click',function(){
            self.fragmentWrap('i')
        })
        this.underlineButton = this.createButton()
        this.underlineButton.innerHTML = '<u>U</u>'
        this.underlineButton.title = 'Underline'
        this.underlineButton.addEventListener('click',function(){
            self.fragmentWrap('u')
        })
        this.strikeButton = this.createButton()
        this.strikeButton.innerHTML = '<s>S</s>'
        this.strikeButton.title = 'Strike'
        this.strikeButton.addEventListener('click',function(){
            self.fragmentWrap('s')
        })

        this.colorButton = this.createButton()
        this.colorButton.title = 'Text color'
        this.colorButton.insertAdjacentHTML('afterbegin',this.buttonPictures.color)
        this.colorButton.addEventListener('click',function(){
            self.createColorPopup()
        })

        /* this.backgroundButton = this.createButton()
        this.backgroundButton.title = 'Text background color'
        this.backgroundButton.insertAdjacentHTML('afterbegin',this.buttonPictures.background)
        this.backgroundButton.addEventListener('click',function(){
            self.createColorPopup(true)
        }) */

        this.ulButton = this.createButton()
        this.ulButton.title = 'Unordered list'
        this.ulButton.addEventListener('click',function(){
            self.setList()
        })
        this.ulButton.insertAdjacentHTML('afterbegin',this.buttonPictures.ul)
        this.olButton = this.createButton()
        this.olButton.title = 'Ordered list'
        this.olButton.addEventListener('click',function(){
            self.setList(true)
        })
        this.olButton.insertAdjacentHTML('afterbegin',this.buttonPictures.ol)

        this.linkButton = this.createButton()
        this.linkButton.title = 'Link'
        this.linkButton.addEventListener('click',function(){
            self.getPopupLink()
        })
        this.linkButton.insertAdjacentHTML('afterbegin',this.buttonPictures.link)

        this.unlinkButton = this.createButton()
        this.unlinkButton.title = 'Remove link'
        this.unlinkButton.addEventListener('click',function(){
            self.unsetLink()
        })
        this.unlinkButton.insertAdjacentHTML('afterbegin',this.buttonPictures.unlink)

        this.imageButton = this.createButton()
        this.imageButton.title = 'Add image'
        this.imageButton.addEventListener('click',function(){
            self.getPopupImage()
        })
        this.imageButton.insertAdjacentHTML('afterbegin',this.buttonPictures.image)
        

        this.buttonbox.append(
            this.headerButton,
            this.boldButton,
            this.italicButton,
            this.underlineButton,
            this.strikeButton,
            this.colorButton,
            //this.backgroundButton,
            this.imageButton,
            this.linkButton,
            this.unlinkButton,
            this.ulButton,
            this.olButton
        )
        /* /buttons */

        this.editorfieldbox = document.createElement('div')
        this.editorfieldbox.classList.add('gnommy-editor-field-box')
        if (this.fieldHeight) {
            this.editorfieldbox.style.height = this.fieldHeight + 'px'
        }

        this.editorfield = document.createElement('div')
        this.editorfield.classList.add('gnommy-editor-field')
        this.editorfield.setAttribute('contenteditable',true)
        
        this.editorresizebox = document.createElement('div')
        this.editorresizebox.classList.add('gnommy-editor-resize-box')

        this.editorresizebox.addEventListener('mousedown',function(e){
            self.resizable = true;
            self.startResizeY = e.pageY
            self.startResizeH = self.editorfieldbox.clientHeight
        })
        this.editorresizebox.addEventListener('mouseup',function(){
            self.resizable = false;
            self.startResizeY = self.currentResizeY
            self.startResizeH = self.currentResizeH
        })
        this.editorbox.addEventListener('mousemove',function(e){
            if (self.resizable) {
                self.currentResizeY = e.pageY - self.startResizeY
                self.currentResizeH = self.startResizeH + self.currentResizeY
                self.editorfieldbox.style.height = self.currentResizeH + 'px'
            }
        })

        this.editorfieldbox.append(this.editorfield)

        this.textfieldbox = document.createElement('div')
        this.textfieldbox.classList.add('gnommy-editor-htmlbox')
        if (this.showHtml) {
            this.textfieldbox.classList.add('active')
        }

        this.footerbox = document.createElement('div')
        this.footerbox.classList.add('gnommy-editor-footer')
        this.footerbox.innerHTML = 'Enter = &lt;br&gt; &nbsp; &nbsp; Ctrl+Enter = &lt;p&gt;'

        this.htmlexpandbutton = document.createElement('div')
        this.htmlexpandbutton.classList.add('gnommy-editor-html-button')
        this.htmlexpandbutton.innerHTML = '&lt;/&gt;';
        this.htmlexpandbutton.setAttribute('title','Show/hide HTML-code')
        this.htmlexpandbutton.addEventListener('click',function(){
            self.textfieldbox.classList.toggle('active')
        })
        this.footerbox.append(this.htmlexpandbutton)

        this.editorbox.append(this.buttonbox)
        this.editorbox.append(this.editorfieldbox)
        if (!this.noResize) {
            this.editorbox.append(this.editorresizebox)
        }
        this.editorbox.append(this.footerbox)
        this.editorbox.append(this.textfieldbox)

        this.textfield.after(this.editorbox)
        this.textfieldbox.append(this.textfield)

        this.editorfield.addEventListener('keydown',function(e){
            if (e.code == 'Enter' || e.code == 'NumpadEnter') {
                let setParagraph = e.ctrlKey
                if (self.pEnter) {
                    setParagraph = !e.ctrlKey
                }
                self.setBreak(setParagraph)
                e.preventDefault();
            }
        })
        this.editorfield.addEventListener('input',function(){
            self.contentClone(this,self.textfield)
        })
        this.editorfield.addEventListener('DOMNodeInserted',function(){
            self.contentClone(this,self.textfield)
        })
        this.editorfield.addEventListener('DOMNodeRemoved',function(){
            self.contentClone(this,self.textfield)
        })
        this.editorfield.addEventListener('DOMCharacterDataModified',function(){
            self.contentClone(this,self.textfield)
        })
        this.editorfield.addEventListener('DOMAttributeNameChanged',function(){
            self.contentClone(this,self.textfield)
        })
        this.editorfield.addEventListener('DOMAttrModified',function(){
            self.contentClone(this,self.textfield)
        })
        this.editorfield.addEventListener('DOMElementNameChanged',function(){
            self.contentClone(this,self.textfield)
        })
        this.editorfield.addEventListener('DOMSubtreeModified',function(){
            self.contentClone(this,self.textfield)
        })
        this.textfield.addEventListener('input',function(){
            self.contentClone(this,self.editorfield,false)
        })
        self.contentClone(this.textfield,self.editorfield,false)
        
        this.setImagesContext()
    }

    setImagesContext() {
        const images = this.editorfield.querySelectorAll('img')
        let self = this
        
        if (images) {
            images.forEach(image => {
                image.addEventListener('mouseover',function(e){
                    self.createImageMenu(image)
                })
                image.addEventListener('mouseout',function(e){
                    if (self.imageMenuBox && e.relatedTarget !== self.imageMenuBox) {
                        self.removeImageMenu()
                    }
                })
            });
        }
    }

    setImageContext(image) {

        let self = this

        image.addEventListener('mouseover',function(e){
            self.createImageMenu(image)
        })
        image.addEventListener('mouseout',function(e){
            if (self.imageMenuBox && e.relatedTarget !== self.imageMenuBox) {
                self.removeImageMenu()
            }
        })
    }

    createColorPopup (bg = false) {
        let selection = null
        selection = document.getSelection();
        if (!selection || !selection.anchorNode) {
            return false
        }
        if (selection && selection.rangeCount > 0) {
            let selectionRange = selection.getRangeAt(0)

            let container = selectionRange.commonAncestorContainer,
                popupClass = 'textcolor'
            
            if (bg) {
                popupClass = 'selectcolor'
            }

            this.openPopup(this.createColor(bg),popupClass)
            this.lastRange = {'startContainer': selectionRange.startContainer, 'startOffset': selectionRange.startOffset, 'endContainer': selectionRange.endContainer, 'endOffset': selectionRange.endOffset, 'container': container}
        }
    }

    setTextColor(color) {
        if (color) {
            let range = new Range()
            range.setStart(this.lastRange.startContainer,this.lastRange.startOffset)
            range.setEnd(this.lastRange.endContainer,this.lastRange.endOffset)
            let fragment = this.unwrap(range.extractContents().childNodes,'span',true,'color')
            range.insertNode(this.wrap(fragment,'span',{'color':color}))
            this.closePopup()
        }
    }

    setBgColor(color) {
        if (color) {
            let range = new Range()
            range.setStart(this.lastRange.startContainer,this.lastRange.startOffset)
            range.setEnd(this.lastRange.endContainer,this.lastRange.endOffset)
            let fragment = this.unwrap(range.extractContents().childNodes,'span',true,'backgroundColor')
            range.insertNode(this.wrap(fragment,'span',{'background-color':color}))
            this.closePopup()
        }
    }

    createColor (bg = false) {
        this.colorBox = document.createElement('div')
        this.colorBox.classList.add('gnommy-editor-color-box')

        this.colorLabel = document.createElement('label')
        this.colorLabel.setAttribute('for','color')
        if (bg) {
            this.colorLabel.innerHTML = 'Select color'
            this.colorRemoveInput = document.createElement('input')
            this.colorRemoveInput.type = 'checkbox'
            this.colorRemoveInput.name = 'transparent'
            this.colorRemoveInput.id = 'transparent'
            this.colorRemoveInputLabel = document.createElement('label')
            this.colorRemoveInputLabel.setAttribute('for','transparent')
            this.colorRemoveInputLabel.innerHTML = 'Remove color'
        }
        else {
            this.colorLabel.innerHTML = 'Text color'
        }
        this.colorInput = document.createElement('input')
        this.colorInput.type = 'color'
        this.colorInput.name = 'color'
        this.colorInput.id = 'color'

        this.linkeditButtonBox = document.createElement('div')
        this.linkeditButtonBox.classList.add('gnommy-editor-linkpopup-buttons')
        this.linkeditOkButton = document.createElement('button')
        this.linkeditOkButton.innerHTML = 'OK'
        this.linkeditCancelButton = document.createElement('button')
        this.linkeditCancelButton.innerHTML = 'Cancel'
        this.linkeditButtonBox.append(this.linkeditOkButton,this.linkeditCancelButton)
        this.colorBox.append(this.colorLabel,this.colorInput)
        if (bg) {
            this.colorBox.append(this.colorRemoveInput,this.colorRemoveInputLabel)
        }
        this.colorBox.append(this.linkeditButtonBox)

        let self = this

        this.linkeditCancelButton.addEventListener('click',function(){
            self.closePopup()
        })

        this.linkeditOkButton.addEventListener('click',function(){
            let color = self.colorInput.value
            
            if (color) {
                if (bg) {
                    if (self.colorRemoveInput.checked) {
                        color = 'transparent'
                    }
                    self.setBgColor(color)
                }
                else {
                    self.setTextColor(color)
                }
            }
        })

        return this.colorBox
    }

    createImageMenu (image,id=null) {
        if (this.imageMenuBox) {
            this.imageMenuBox.remove()
        }
        this.imageMenuBox = document.createElement('div')
        const editButton = document.createElement('button')
        const deleteButton = document.createElement('button')
        this.imageMenuBox.classList.add('gnommy-editor-image-menu-box')
        if (id) {
            this.imageMenuBox.dataset.imgid = id
        }
        editButton.innerHTML = 'Edit'
        deleteButton.innerHTML = 'Delete'
        this.imageMenuBox.append(editButton, deleteButton)
        let imageCoords = this.getCoordsInBox(image)
        
        this.imageMenuBox.style.top = (imageCoords.top + 5) + 'px'
        this.imageMenuBox.style.right = (imageCoords.right - 10) + 'px'
        this.editorfieldbox.append(this.imageMenuBox)

        let self = this
        editButton.addEventListener('click',function(){
            self.editImageBox(image)
        })
        deleteButton.addEventListener('click',function(){
            self.deleteImage(image,this.imageMenuBox)
        })
        return this.imageMenuBox
    }

    removeImageMenu () {
        this.imageMenuBox.remove()
    }

    editImageBox (image) {
        this.openPopup(this.editImagePopup(image),'free')
    }

    deleteImage (image) {
        if (image) {
            this.imageMenuBox.remove()
            this.imageMenuBox = null
            let range = new Range();
            range.selectNode(image)
            range.deleteContents()
            range.collapse()
        }
    }

    openPopup(contentElement,addclass=null) {
        this.popupwindow.append(contentElement)
        this.popuplayer.classList.add('active')
        if (!this.isEmpty(addclass)) {
            this.popupwindow.classList.add(addclass)
        }
    }

    closePopup() {
        this.popuplayer.classList.remove('active')
        this.popupwindow.className = ''
        this.popupwindow.classList.add('gnommy-editor-popup-window')
        this.popupwindow.innerHTML = ''
    }


    contextMenu (self,e) {
        
    }

    getCoordsInField(element) {
        if (element) {
            let fieldBox = this.editorfield.getBoundingClientRect(),
                elementBox = element.getBoundingClientRect()
            return {
                top: elementBox.top - fieldBox.top,
                right: fieldBox.right - elementBox.right,
                bottom: fieldBox.bottom - elementBox.bottom,
                left: elementBox.left - fieldBox.left
            }
        }
        return {
            top: null,
            right: null,
            bottom: null,
            left: null
        }
    }

    getCoordsInBox(element) {
        if (element) {
            let fieldBox = this.editorfieldbox.getBoundingClientRect(),
                elementBox = element.getBoundingClientRect()

            return {
                top: elementBox.top - fieldBox.top + this.editorfieldbox.scrollTop,
                right: fieldBox.right - elementBox.right,
                bottom: fieldBox.bottom - elementBox.bottom,
                left: elementBox.left - fieldBox.left + this.editorfieldbox.scrollLeft
            }
        }
        return {
            top: null,
            right: null,
            bottom: null,
            left: null
        }
    }

    openPopup(contentElement,addclass=null) {
        this.popupwindow.append(contentElement)
        this.popuplayer.classList.add('active')
        if (!this.isEmpty(addclass)) {
            this.popupwindow.classList.add(addclass)
        }
    }

    closePopup() {
        this.popuplayer.classList.remove('active')
        this.popupwindow.className = ''
        this.popupwindow.classList.add('gnommy-editor-popup-window')
        this.popupwindow.innerHTML = ''
    }

    setList(number=false,type=null) {
        let selection = document.getSelection()
        let selectionRange = selection.getRangeAt(0)

        if ((selection.anchorNode.parentNode.closest('.gnommy-editor-field') || selection.anchorNode.parentNode.classList.contains('gnommy-editor-field')) && selection.anchorNode.parentNode.closest('.gnommy-editor-box') === this.editorbox) {

            let listNode = selectionRange.commonAncestorContainer,
                    isStart = false,
                    isEnd = false,
                    startLi = this.getParent(selectionRange.startContainer,'LI'),
                    endLi = this.getParent(selectionRange.endContainer,'LI'),
                    fragment

            if (listNode.nodeName == 'OL' || listNode.nodeName == 'UL') {
                
                if (startLi === listNode.firstChild) {
                    isStart = true
                }
                if (endLi === listNode.lastChild) {
                    isEnd = true
                } 
                selectionRange.setStartBefore(startLi)
                selectionRange.setEndAfter(endLi)

                fragment = selectionRange.extractContents()

                if (isStart && !isEnd) {
                    selectionRange.setStartBefore(listNode)
                    selectionRange.collapse(true)
                    selectionRange.insertNode(this.unwrap(fragment.childNodes,'li'))
                    return true
                }
                else if (!isStart && isEnd) {
                    selectionRange.setEndAfter(listNode)
                    selectionRange.collapse()
                    selectionRange.insertNode(this.unwrap(fragment.childNodes,'li'))
                    selectionRange.collapse()
                    return true
                }
                else if (isStart && isEnd) {
                    selectionRange.selectNode(listNode)
                    selectionRange.deleteContents()
                    selectionRange.insertNode(this.unwrap(fragment.childNodes,'li'))
                    selectionRange.collapse()
                    return true
                }
                else { // !isStart && !isEnd
                    let newLi = document.createElement('li'),
                        libr = document.createElement('br')
                    newLi.append(libr)
                    selectionRange.insertNode(newLi)
                    selectionRange.selectNode(libr)
                    selectionRange.deleteContents()
                    fragment = this.unwrap(fragment.childNodes,'li')
                }
            }
            else {
                if (selectionRange.commonAncestorContainer.nodeName == 'LI') {
                    let li = selectionRange.commonAncestorContainer,
                    endLineNode = this.getLineEndNode(selectionRange.endContainer)
                    selectionRange.setEndAfter(endLineNode)
                }
                else {
                    let startLineNode = this.getLineStartNode(selectionRange.startContainer),
                        endLineNode = this.getLineEndNode(selectionRange.endContainer)
                    selectionRange.setStartBefore(startLineNode)
                    selectionRange.setEndAfter(endLineNode)
                }
                fragment = selectionRange.extractContents()
            }
            
            let items = [], i = 0 
            let self = this
            Array.from(fragment.childNodes).forEach(function(node) {
                if (items[i] == undefined) {
                    items[i] = ''
                }
                if (node.nodeName == 'BR') {
                    i++
                }
                else if (node.nodeName == 'P') {
                    i++
                    items[i] = node.innerHTML
                }
                else {
                    if (node.nodeType != 3) {
                        let tmpElement = document.createElement('div')
                        tmpElement.append(node)
                        items[i] += tmpElement.innerHTML
                    }
                    else {
                        items[i] += node.textContent
                    }
                }
            })
            
            let listType = number ? 'ol' : 'ul'
            const list = document.createElement(listType)
            if (items.length > 0) {
                items.forEach(item => {
                    if (item != '') {
                        let element = document.createElement('li')
                        element.insertAdjacentHTML('afterbegin',item)
                        list.append(element)
                    }
                });
            }

            selectionRange.insertNode(list)
            selectionRange.collapse()

        }
    }

    setBreak(setParagraph=false) {
        let selection = document.getSelection(),
            selectionRange = null

        if (selection && selection.rangeCount > 0) {
            selectionRange = selection.getRangeAt(0)
        }

        if (selectionRange) {
            selectionRange.deleteContents()

            let li = this.getParent(selectionRange.commonAncestorContainer,'LI')
            if (!li && selectionRange.commonAncestorContainer.nodeName == 'LI') {
                li = selectionRange.commonAncestorContainer
            }
            
            if (li) {
                selectionRange.setStart(li,0)
                let startFragment = selectionRange.extractContents()
                selectionRange.selectNode(li)
                let endFragment = selectionRange.extractContents()
                const startLi = document.createElement('li')
                startLi.append(startFragment)
                selectionRange.insertNode(startLi)
                selectionRange.setStartAfter(startLi)
                selectionRange.collapse(true)

                if (endFragment.textContent.length > 0) {
                    selectionRange.insertNode(endFragment)
                }
                
                selectionRange.collapse(true)
                return true
            }

            const paragraph = this.getParagraph(selection.anchorNode)

            if (setParagraph) {
                if (paragraph) {
                    selectionRange.setStart(paragraph,0)
                    let startFragment = selectionRange.extractContents()
                    selectionRange.selectNode(paragraph)
                    let endFragment = selectionRange.extractContents()
                    const newParagraph = document.createElement('p')
                    const newBreak = document.createElement('br')
                    newParagraph.append(newBreak)
                    if (startFragment.textContent.length > 0) {
                        const startParagraph = document.createElement('p')
                        startParagraph.append(startFragment)
                        selectionRange.insertNode(startParagraph)
                        selectionRange.setStartAfter(startParagraph)
                        selectionRange.collapse(true)
                    }
                    
                    selectionRange.insertNode(newParagraph)
                    selectionRange.setStartAfter(newParagraph)
                    selectionRange.collapse(true)
                    if (endFragment.textContent.length > 0) {
                        selectionRange.insertNode(endFragment)
                    }
                    
                    selectionRange.setStartAfter(newBreak)
                    selectionRange.collapse(true)
                }
                else {
                    const newParagraph = document.createElement('p')
                    const newBreak = document.createElement('br')
                    newParagraph.append(newBreak)
                    selectionRange.insertNode(newParagraph)
                    selectionRange.setStartAfter(newBreak)
                    selectionRange.collapse(true)
                }
            }
            else {
                const newBreak = document.createElement('br')
                selectionRange.insertNode(newBreak)
                selectionRange.setStartAfter(newBreak)
                selectionRange.collapse(true)
            }
        }
    }

    contentClone(from,to,asText = true) {
        if (typeof from == 'string'){
            from = document.querySelector(from)
        }
        if (typeof to == 'string'){
            to = document.querySelector(to)
        }
        if (from instanceof HTMLElement && to instanceof HTMLElement) {
            let value = from.innerHTML
            if (from.tagName.toLowerCase() === 'input' || from.tagName.toLowerCase() === 'textarea') {
                value = from.value
            }
            if (to.tagName.toLowerCase() === 'input') {
                to.value = value
            }
            else {
                if (asText) {
                    to.innerHTML = value
                }
                else {

                    to.innerHTML = ''
                    to.insertAdjacentHTML('afterbegin', value)
                }
            }
        }
    }

    getParagraph (element) {
        if (element && (element instanceof DocumentFragment || element instanceof HTMLElement || element.nodeType == 3)) {
            if (element.tagName == 'P') {
                return element
            }
            else {
                return this.getParagraph (element.parentElement)
            }
        }
        return false
    }

    getParent (element,nodeName,hasStyle=false) {
        if (typeof nodeName == 'string' && nodeName.slice(0,1) != '#') {
            nodeName = nodeName.toUpperCase()
        }
        if (this.isEmpty(nodeName)) {
            return false
        }
        if (element && (element instanceof DocumentFragment || element instanceof HTMLElement || element.nodeType == 3)) {
            if (element.tagName == nodeName && (!hasStyle || element.style[hasStyle])) {
                return element
            }
            else {
                return this.getParent (element.parentElement,nodeName)
            }
        }
        return false
    }

    getNextSibling(element,nodeName) {
        if (typeof nodeName == 'string' && nodeName.slice(0,1) != '#') {
            nodeName = nodeName.toUpperCase()
        }
        if (this.isEmpty(nodeName)) {
            return false
        }
        if (element && (element instanceof DocumentFragment || element instanceof HTMLElement || element.nodeType == 3)) {
            if (element.nextSibling.nodeName == nodeName || element.nextSibling == this.editorfield) {
                return element
            }
            else {
                return this.getNextSibling (element.nextSibling, nodeName)
            }
        }
        return false
    }

    getPreviousSibling(element,nodeName) {
        if (typeof nodeName == 'string' && nodeName.slice(0,1) != '#') {
            nodeName = nodeName.toUpperCase()
        }
        if (this.isEmpty(nodeName)) {
            return false
        }
        if (element && (element instanceof DocumentFragment || element instanceof HTMLElement || element.nodeType == 3)) {
            if (element.previousSibling.nodeName == nodeName || element.previousSibling == this.editorfield) {
                return element
            }
            else {
                return this.getNextSibling (element.previousSibling, nodeName)
            }
        }
        return false
    }

    getLineStartNode(element) {
        if (element && (element instanceof DocumentFragment || element instanceof HTMLElement || element.nodeType == 3)) {
            if (element.previousSibling === null) {
                if (element.parentNode === this.editorfield || element.parentNode.nodeName == 'LI' || element.parentNode.nodeName == 'UL' || element.parentNode.nodeName == 'OL') {
                    return element
                }
                else if (element.parentNode.nodeName == 'P' || element.parentNode.nodeName == 'DIV') {
                    return element.parentNode
                }
                else {
                    return this.getLineStartNode(element.parentNode)
                }
            }
            else if (element.previousSibling.nodeName == 'DIV' || element.previousSibling.nodeName == 'P' || element.previousSibling.nodeName == 'BR' || element.previousSibling.nodeName == 'LI' || element.previousSibling.nodeName == 'UL' || element.previousSibling.nodeName == 'OL' || element.previousSibling == this.editorfield) {
                return element
            }
            else {
                return this.getLineStartNode (element.previousSibling)
            }
        }
        return false
    }

    getLineEndNode(element) {
        if (element && (element instanceof DocumentFragment || element instanceof HTMLElement || element.nodeType == 3)) {
            if (element.nextSibling === null) {
                if (element.parentNode === this.editorfield || element.parentNode.nodeName == 'LI' || element.parentNode.nodeName == 'UL' || element.parentNode.nodeName == 'OL') {
                    return element
                }
                else if (element.parentNode.nodeName == 'P' || element.parentNode.nodeName == 'DIV') {
                    return element.parentNode
                }
                else {
                    return this.getLineStartNode(element.parentNode)
                }
            }
            else if (element.nextSibling === null || element.nextSibling.nodeName == 'DIV' || element.nextSibling.nodeName == 'P' || element.nextSibling.nodeName == 'BR' || element.nextSibling.nodeName == 'LI' || element.nextSibling.nodeName == 'UL' || element.nextSibling.nodeName == 'OL' || element.nextSibling == this.editorfield) {
                return element
            }
            else {
                return this.getLineEndNode (element.nextSibling)
            }
        }
        return false
    }
    
    isEmpty (value) {
        if (value === null || value === false || value === undefined || value === NaN || value == '' || value === {} || value === [] || value == 'undefined') {
            return true
        }
        return false
    }
    
    objectToSyleStr (obj = {}) {
        let str = ''
        if (!this.isEmpty(obj)) {
            for (var property in obj) {
                str += property + ':' + obj[property] + ';'
            }
        }
        return str
    }

    elementToString (element) {
        let string = '';
        if (element instanceof DocumentFragment || element instanceof HTMLElement) {
            let tmpElement = document.createElement('div')
            tmpElement.append(element)
            string = tmpElement.innerHTML;
        }
        else if (typeof element == 'string') {
            string = element;
        }
        return string
    }
    
    wrap (element, wrapper=null, style='') {
        if (element && (element instanceof DocumentFragment || element instanceof HTMLElement) && !this.isEmpty(wrapper)) {
            wrapper = document.createElement(wrapper);
            if (!this.isEmpty(style)) {
                if (typeof style == 'object') {
                    style = this.objectToSyleStr(style)
                }
                wrapper.setAttribute('style',style)
            }
            wrapper.append(element)
            return wrapper
        }
        return element
    }

    unwrap(nodeList,wrapperName,deep=true,hasStyle=false) {
        let self = this,
            fragment = new DocumentFragment()
        Array.from(nodeList).forEach(function(node) {
            if (deep && node.childNodes && node.childNodes.length > 0 && (node.childNodes.length > 1 || node.childNodes[0].nodeName != '#text')) {
                fragment.append(self.unwrap(node.childNodes,wrapperName,deep,hasStyle))
            }
            else if (wrapperName.toUpperCase() == node.nodeName && (!hasStyle || (hasStyle && node.style[hasStyle]))) {
                fragment.append(node.textContent)
                if (node.nodeName == 'LI') {
                    fragment.append(document.createElement('br'))
                }
            }
            else {
                fragment.append(node)
            }
        });
        return fragment
    }

    buttonPictures = {
        'ul': `<svg width="18px" height="18px" viewBox="0 0 30 30" version="1.1" id="svg5"><defs id="defs2" /><g id="layer1"><path id="rect1064-5" style="stroke-width:3;stroke-linecap:round" d="m 10,24 h 18 v 2 H 10 Z M 7.9430017,24.991791 A 2.9715009,2.991791 0 0 1 4.9715009,27.983582 2.9715009,2.991791 0 0 1 2,24.991791 2.9715009,2.991791 0 0 1 4.9715009,22 2.9715009,2.991791 0 0 1 7.9430017,24.991791 Z M 10,14 h 18 v 2 H 10 Z M 7.9430013,14.991791 A 2.9715009,2.991791 0 0 1 4.9715004,17.983582 2.9715009,2.991791 0 0 1 1.9999995,14.991791 2.9715009,2.991791 0 0 1 4.9715004,12 2.9715009,2.991791 0 0 1 7.9430013,14.991791 Z M 10,4 H 28 V 6 H 10 Z M 7.9430017,4.9917912 A 2.9715009,2.991791 0 0 1 4.9715009,7.9835823 2.9715009,2.991791 0 0 1 2,4.9917912 2.9715009,2.991791 0 0 1 4.9715009,2.0000002 2.9715009,2.991791 0 0 1 7.9430017,4.9917912 Z" /></g></svg>`,
        'ol': `<svg width="18px" height="18px" viewBox="0 0 30 30" version="1.1" id="svg5"><defs id="defs2" /><g id="layer1"><g aria-label="1" id="text14687" style="font-size:8.50085px;line-height:1.25;font-family:'Barlow ExtraBold';-inkscape-font-specification:'Barlow ExtraBold, ';letter-spacing:0px;word-spacing:0px;stroke-width:0.212522" /><g aria-label="2" id="text14687-3" style="font-size:8.50085px;line-height:1.25;font-family:'Barlow ExtraBold';-inkscape-font-specification:'Barlow ExtraBold, ';letter-spacing:0px;word-spacing:0px;stroke-width:0.212522" /><path id="path22480" d="m 6.6297508,25.08727 q 0.1700169,0.408041 0.1700169,0.875588 0,0.459046 -0.1700169,0.858586 -0.2210221,0.552555 -0.7225723,0.875587 -0.5015501,0.314532 -1.1816181,0.314532 -0.6630663,0 -1.1731172,-0.323033 -0.510051,-0.331533 -0.739574,-0.892589 -0.1360136,-0.340034 -0.1615161,-0.748075 0,-0.10201 0.1020102,-0.10201 h 1.2241223 q 0.1020102,0 0.1105111,0.10201 0.017002,0.212522 0.1020102,0.408041 0.068007,0.170017 0.2040204,0.263526 0.1360136,0.08501 0.3230323,0.08501 0.3570357,0 0.5355535,-0.323032 0.1190119,-0.212522 0.1190119,-0.561056 0,-0.340034 -0.1190119,-0.59506 -0.1785178,-0.314531 -0.5440544,-0.314531 -0.093509,0 -0.2125212,0.06801 -0.1105111,0.05951 -0.2890289,0.187019 -0.042504,0.0255 -0.068007,0.0255 -0.051005,0 -0.076508,-0.051 L 3.4589338,24.390201 q -0.025502,-0.04251 -0.025502,-0.06801 0,-0.051 0.042504,-0.07651 l 1.3091309,-0.994599 q 0.017002,-0.017 0.0085,-0.034 0,-0.017 -0.025502,-0.017 H 2.9063786 q -0.042504,0 -0.076508,-0.0255 -0.025502,-0.034 -0.025502,-0.07651 v -1.0031 q 0,-0.0425 0.025502,-0.06801 0.034003,-0.034 0.076508,-0.034 h 3.7063705 q 0.042504,0 0.068007,0.034 0.034003,0.0255 0.034003,0.06801 v 1.130613 q 0,0.07651 -0.059506,0.127513 l -1.0286028,0.807581 q -0.017002,0.017 -0.017002,0.034 0.0085,0.017 0.034003,0.0255 0.680068,0.178517 0.9860986,0.867086 z M 4.6660545,16.747922 q -0.017002,0.017 -0.0085,0.034 0.0085,0.017 0.034003,0.017 h 2.1677167 q 0.042504,0 0.068007,0.034 0.034003,0.0255 0.034003,0.06801 v 1.0031 q 0,0.0425 -0.034003,0.07651 -0.025503,0.0255 -0.068007,0.0255 h -3.986899 q -0.042504,0 -0.076508,-0.0255 -0.025502,-0.034 -0.025502,-0.07651 v -0.943594 q 0,-0.07651 0.051005,-0.127513 0.510051,-0.476048 1.3516351,-1.368637 l 0.510051,-0.535553 q 0.7735773,-0.79908 0.7735773,-1.164617 0,-0.246524 -0.1870187,-0.408041 -0.1785178,-0.161516 -0.4675467,-0.161516 -0.2890289,0 -0.4675468,0.161516 -0.1785178,0.161517 -0.1785178,0.425043 v 0.20402 q 0,0.04251 -0.034003,0.07651 -0.025503,0.0255 -0.068007,0.0255 H 2.8298709 q -0.042504,0 -0.076508,-0.0255 -0.025503,-0.034 -0.025503,-0.07651 v -0.425042 q 0.034003,-0.476048 0.3145315,-0.833083 0.280528,-0.357036 0.7395739,-0.544055 0.4590459,-0.195519 1.020102,-0.195519 0.6290628,0 1.0966096,0.238023 0.4760476,0.229523 0.7225722,0.629063 0.2550255,0.39954 0.2550255,0.884089 0,0.348534 -0.170017,0.70557 -0.170017,0.357036 -0.510051,0.756576 Q 5.9581828,15.489791 5.6606531,15.78732 5.3631233,16.08485 4.8360707,16.5864 Z M 4.2025246,2.0691242 q 0.068007,-0.025503 0.1275127,-0.025503 h 1.2411241 q 0.042504,0 0.068007,0.034003 0.034003,0.025503 0.034003,0.068007 v 5.7465744 q 0,0.042504 -0.034003,0.076508 -0.025503,0.025502 -0.068007,0.025502 H 4.3640407 q -0.042504,0 -0.076508,-0.025502 -0.025503,-0.034003 -0.025503,-0.076508 V 3.3952567 q 0,-0.017002 -0.017002,-0.034003 -0.017002,-0.017002 -0.034003,-0.0085 l -0.8330832,0.1955195 -0.034003,0.0085 q -0.076508,0 -0.076508,-0.093509 L 3.2504294,2.5706743 q 0,-0.085008 0.076508,-0.1190119 z M 10,24 h 18 v 2 H 10 Z m 0,-10 h 18 v 2 H 10 Z M 10,4 H 28 V 6 H 10 Z" style="font-size:8.50085px;line-height:1.25;font-family:'Barlow ExtraBold';-inkscape-font-specification:'Barlow ExtraBold, ';letter-spacing:0px;word-spacing:0px;stroke-width:0.212522" /></g></svg>`,
        'link': `<svg width="18px" height="18px" viewBox="0 0 30 30" version="1.1" id="svg5"><defs id="defs2" /><g id="layer1"><g aria-label="a" id="text14687" style="font-size:8.50085px;line-height:1.25;font-family:'Barlow ExtraBold';-inkscape-font-specification:'Barlow ExtraBold, ';letter-spacing:0px;word-spacing:0px;stroke-width:0.212522" /><g aria-label="b" id="text14687-3" style="font-size:8.50085px;line-height:1.25;font-family:'Barlow ExtraBold';-inkscape-font-specification:'Barlow ExtraBold, ';letter-spacing:0px;word-spacing:0px;stroke-width:0.212522" /><path id="path30795" style="color:#000000;fill:#000000;stroke-width:0.860644;stroke-linecap:round;-inkscape-stroke:none" d="m 17.52095,15.640807 c 1.300807,-0.567273 2.880881,-0.321499 3.939699,0.737319 l 5.477102,5.477102 c 1.378884,1.378883 1.378884,3.641794 0,5.020676 -1.378882,1.378883 -3.641792,1.378883 -5.020676,0 l -5.477102,-5.477102 c -1.06846,-1.068461 -1.309,-2.667715 -0.721618,-3.975167 l 1.620754,1.66488 c 0.04396,0.340773 0.200673,0.671545 0.47014,0.941012 l 5.477103,5.477101 c 0.64399,0.643991 1.638134,0.643991 2.282124,0 0.64399,-0.643989 0.64399,-1.638134 0,-2.282124 l -5.477102,-5.477102 c -0.285473,-0.285475 -0.639754,-0.4444 -1.001839,-0.476781 z M 11.154289,9.8325194 a 0.89059384,0.89059384 0 0 0 -1.2599225,0 0.89059384,0.89059384 0 0 0 0,1.2599226 l 9.1285015,9.128502 a 0.89059384,0.89059384 0 0 0 1.258733,-0.0011 0.89059384,0.89059384 0 0 0 0.0011,-1.258734 z m 3.157322,2.5989476 C 14.878883,11.130661 14.63311,9.5505883 13.574293,8.4917699 L 8.0971935,3.0146693 c -1.3788833,-1.3788834 -3.6417927,-1.3788834 -5.0206763,0 -1.3788832,1.3788835 -1.3788832,3.6417923 0,5.0206758 l 5.4771015,5.4770999 c 1.0684578,1.068461 2.6677133,1.309 3.9751643,0.721618 L 10.863903,12.61331 C 10.52313,12.56935 10.192359,12.412637 9.9228935,12.14317 L 4.4457925,6.6660699 c -0.6439903,-0.6439904 -0.6439903,-1.6381351 0,-2.2821254 0.6439903,-0.6439904 1.6381347,-0.6439904 2.282125,0 l 5.4771005,5.4771012 c 0.285474,0.2854723 0.444399,0.6397523 0.47678,1.0018363 z" /></g></svg>`,
        'unlink': `<svg width="18px" height="18px" viewBox="0 0 30 30" version="1.1" id="svg5"><defs id="defs2" /><g id="layer1"><g aria-label="a" id="text14687" style="font-size:8.50085px;line-height:1.25;font-family:'Barlow ExtraBold';-inkscape-font-specification:'Barlow ExtraBold, ';letter-spacing:0px;word-spacing:0px;stroke-width:0.212522" /><g aria-label="b" id="text14687-3" style="font-size:8.50085px;line-height:1.25;font-family:'Barlow ExtraBold';-inkscape-font-specification:'Barlow ExtraBold, ';letter-spacing:0px;word-spacing:0px;stroke-width:0.212522" /><path id="path32213" style="fill:#000000;fill-opacity:1;stroke:none;stroke-width:0.135402px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" d="M 12.529144,15.861136 9.9345687,17.966309 9.344536,16.357302 Z m 0.945985,1.059781 0.09348,3.535012 -1.730809,-0.542465 z m 3.720258,-3.018249 2.594575,-2.105173 0.590033,1.609007 z m -0.912233,-0.917738 -0.09348,-3.535011 1.73081,0.5424641 z m 1.237796,2.655877 c 1.300807,-0.567273 2.880881,-0.321499 3.939699,0.737319 l 5.477102,5.477102 c 1.378884,1.378883 1.378884,3.641794 0,5.020676 -1.378882,1.378883 -3.641792,1.378883 -5.020676,0 l -5.477102,-5.477102 c -1.06846,-1.068461 -1.309,-2.667715 -0.721618,-3.975167 l 1.620754,1.66488 c 0.04396,0.340773 0.200673,0.671545 0.47014,0.941012 l 5.477103,5.477101 c 0.64399,0.643991 1.638134,0.643991 2.282124,0 0.64399,-0.643989 0.64399,-1.638134 0,-2.282124 l -5.477102,-5.477102 c -0.285473,-0.285475 -0.639754,-0.4444 -1.001839,-0.476781 z m -2.640393,0.437826 4.142311,4.142311 c 0.348015,0.346991 0.911325,0.346498 1.258733,-0.0011 0.347599,-0.347408 0.348091,-0.910719 0.0011,-1.258734 l -4.197363,-4.197445 c -1.466896,-0.223377 0.224293,1.626651 -1.204781,1.314968 z m -0.06767,-2.587448 -3.658594,-3.6586656 c -0.347848,-0.348087 -0.912075,-0.348087 -1.2599225,0 -0.3480871,0.3478476 -0.3480871,0.9120746 0,1.2599226 l 3.5937035,3.593704 c 1.652874,0.236978 0.04257,-1.184713 1.324813,-1.194961 z M 14.311611,12.431467 C 14.878883,11.130661 14.63311,9.5505883 13.574293,8.4917699 L 8.0971935,3.0146693 c -1.3788833,-1.3788834 -3.6417927,-1.3788834 -5.0206763,0 -1.3788832,1.3788835 -1.3788832,3.6417923 0,5.0206758 l 5.4771015,5.4770999 c 1.0684578,1.068461 2.6677133,1.309 3.9751643,0.721618 L 10.863903,12.61331 C 10.52313,12.56935 10.192359,12.412637 9.9228935,12.14317 L 4.4457925,6.6660699 c -0.6439903,-0.6439904 -0.6439903,-1.6381351 0,-2.2821254 0.6439903,-0.6439904 1.6381347,-0.6439904 2.282125,0 l 5.4771005,5.4771012 c 0.285474,0.2854723 0.444399,0.6397523 0.47678,1.0018363 z" /></g></svg>`,
        'image': `<svg width="18px" height="18px" viewBox="0 0 30 30" version="1.1" id="svg5"><defs id="defs2" /><g id="layer1"><g aria-label="a" id="text14687" style="font-size:8.50085px;line-height:1.25;font-family:'Barlow ExtraBold';-inkscape-font-specification:'Barlow ExtraBold, ';letter-spacing:0px;word-spacing:0px;stroke-width:0.212522" /><g aria-label="b" id="text14687-3" style="font-size:8.50085px;line-height:1.25;font-family:'Barlow ExtraBold';-inkscape-font-specification:'Barlow ExtraBold, ';letter-spacing:0px;word-spacing:0px;stroke-width:0.212522" /><path id="path32546" style="fill:#000000;fill-opacity:1;stroke:none;stroke-width:0.999999px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" transform="scale(0.26458333)" d="M 49.134766,45.353516 26.457031,75.589844 V 98.267578 H 86.929688 V 64.251953 L 75.589844,52.914062 64.251953,64.251953 Z m 0,4 9.949218,12.4375 c 3.038457,3.79807 -1.59889,2.487883 -4.617187,3.667968 -2.115644,0.827169 -2.323405,4.711514 -3.304688,4.582032 -2.265516,-0.298923 -2.079626,-5.578141 -4.421875,-4.699219 -6.648907,2.494976 -11.979434,9.7537 -9.333984,-0.351563 z m 26.455078,7.560546 5.603515,5.601563 c -1.095031,0.35743 -2.399246,-1.443529 -2.02539,2.507813 -1.637182,-0.189619 -2.869768,-4.213703 -4.97461,0.0625 -0.88004,-0.175182 -1.563768,-0.746943 -1.996093,-1.84375 -0.540405,-1.371013 -3.028216,0.950253 -4.91211,2.070312 l 2.285156,-2.378906 z M 14.992618,7.4335632 V 11.338583 109.73179 H 98.393209 V 7.4335632 Z m 7.81004,7.8100388 H 90.583171 V 101.92175 H 22.802658 Z" /></g></svg>`,
        'background': `<svg width="18px" height="18px" viewBox="0 0 30 30" version="1.1"><defs id="defs2" /><g id="layer1"><path id="rect3759" style="fill:#000000;fill-opacity:1;stroke:none;stroke-width:2.3811;stroke-linecap:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1" d="M 7.5585938 7.5585938 L 7.5585938 105.82617 L 105.82617 105.82617 L 105.82617 7.5585938 L 7.5585938 7.5585938 z M 49.777344 15.117188 L 63.701172 15.117188 L 98.300781 98.267578 L 85.53125 98.267578 L 77.261719 76.9375 L 36.339844 76.9375 L 28.070312 98.267578 L 15.117188 98.267578 L 49.777344 15.117188 z M 56.708984 26.201172 L 40.048828 67.580078 L 73.431641 67.580078 L 56.708984 26.201172 z " transform="scale(0.26458333)" /><g aria-label="A" transform="matrix(0.88533366,0,0,0.81089007,2.2488146,2.331147)" id="text2709" style="font-size:37.2161px;letter-spacing:0px;word-spacing:0px;fill:#ffffff;fill-opacity:1;stroke:none;stroke-width:0.930404;stroke-opacity:1" /></g></svg>`,
        'color': `<svg width="16px" height="16px" viewBox="0 0 30 30" version="1.1" id="svg5"><defs id="defs2" /><g id="layer1"><g aria-label="A" transform="scale(1.0448834,0.95704457)" id="text2709" style="font-size:37.2161px;letter-spacing:0px;word-spacing:0px;stroke-width:0.63;fill:none;stroke:#000000"><path style="color:#000000;fill:#000000;stroke:none;-inkscape-stroke:none" d="M 12.119141,1.7421875 12.041016,1.9453125 1.5214844,29.503906 H 6.0703125 L 8.5429688,22.542969 H 20.328125 l 2.470703,6.960937 h 4.496094 L 16.714844,1.7421875 Z m 0.433593,0.6308594 H 16.28125 L 26.380859,28.873047 H 23.244141 L 20.771484,21.914062 H 8.0976563 L 5.6269531,28.873047 H 2.4355469 Z M 14.40625,4.765625 14.111328,5.5644531 8.9765625,19.490234 H 19.857422 Z m 0.002,1.8183594 4.544922,12.2773436 H 9.8808594 Z" id="path3509" /></g></g></svg>`

    }
}