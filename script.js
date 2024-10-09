let contador = 2; // Para contar os campos adicionados
let contadorEdit = parseInt(document.getElementById('contador-edit').value, 10);
function adicionarPasso() {
    // Cria um novo div para o campo
    const novoDiv = document.createElement('div');
    novoDiv.classList.add('step-container');
    novoDiv.setAttribute('id', 'step-container-' + contador);

    // Cria um novo label para o campo
    const novoLabel = document.createElement('label');
    novoLabel.setAttribute('for', 'passo' + contador);
    novoLabel.textContent = '';

    // Cria um novo input para o campo
    const novoInput = document.createElement('textarea');
    novoInput.setAttribute('class', 'step-text');
    novoInput.setAttribute('rows', '10');
    novoInput.setAttribute('id', 'passo' + contador);
    novoInput.setAttribute('name', 'passo' + contador);
    novoInput.setAttribute('placeholder', 'Digite o passo...');

    // Cria input de imagem
    const novoInputImage = document.createElement('input');
    novoInputImage.setAttribute('type', 'file');
    novoInputImage.setAttribute('class', 'input-image');
    novoInputImage.setAttribute('id', 'image' + contador);
    novoInputImage.setAttribute('name', 'image' + contador);
    novoInputImage.setAttribute('onchange', 'previewImage(this)');
    novoInputImage.setAttribute('style', 'display: none;')

    // Crie o elemento do ícone
    const iconAnex = document.createElement('i');
    iconAnex.classList.add('fas', 'fa-image');
    // Cria Botão de input-image
    //<button type="button" id="input-image" class="input-image" onclick="clickInputFile()">teste</button>
    const novoBtnInputImage = document.createElement('button');
    novoBtnInputImage.textContent = ' Anexar Imagem ao Passo';
    novoBtnInputImage.setAttribute('type', 'button');
    novoBtnInputImage.setAttribute('id', 'input-image');
    novoBtnInputImage.setAttribute('class', 'btn-input-image');
    novoBtnInputImage.setAttribute('onclick', 'clickInputFile(' + contador +')');

    // Adicione o ícone ao botão
    novoBtnInputImage.prepend(iconAnex);

    // Cria um novo div para pré-visualização da imagem
    const divImagePreview = document.createElement('div');
    divImagePreview.setAttribute('id', 'image-preview' + contador);
    divImagePreview.classList.add('image-preview');

    // Criar icone de lixeira
    //<i class="fas fa-trash"></i>
    const iconDelete = document.createElement('i');
    iconDelete.classList.add('fas', 'fa-trash');

    // Cria um botão de deletar
    const btnDelete = document.createElement('button');
    btnDelete.textContent = ' Deletar Passo';
    btnDelete.setAttribute('type', 'button');
    btnDelete.setAttribute('onclick', 'deletarPasso(' + contador + ')');
    btnDelete.setAttribute('class', 'delete-step');

    // Adicione o ícone ao botão
    btnDelete.prepend(iconDelete);

    // Adiciona o label, input, div de pré-visualização e botão de deletar ao novo div
    novoDiv.appendChild(novoLabel);
    novoDiv.appendChild(novoInput);
    novoDiv.appendChild(novoInputImage);
    novoDiv.appendChild(novoBtnInputImage)
    novoDiv.appendChild(divImagePreview);
    novoDiv.appendChild(btnDelete);

    // Adiciona o novo div ao formulário antes do contêiner de botões
    const formulario = document.getElementById('form-include');
    const btnAdd = formulario.querySelector('.btns-form');

    if (formulario.contains(btnAdd)) {
        formulario.insertBefore(novoDiv, btnAdd);
    }

    // Incrementa o contador
    contador++;
}

function adicionarPassoEdit() {
    //const elementContador = document.getElementById('contador-edit')
    // Cria um novo div para o campo
    const novoDiv = document.createElement('div');
    novoDiv.classList.add('step-container');
    novoDiv.setAttribute('id', 'step-container-' + contadorEdit);

    // Cria um novo label para o campo
    const novoLabel = document.createElement('label');
    novoLabel.setAttribute('for', 'passo' + contadorEdit);
    novoLabel.textContent = '';

    // Cria um novo input para o campo
    const novoInput = document.createElement('textarea');
    novoInput.setAttribute('class', 'step-text');
    novoInput.setAttribute('rows', '10');
    novoInput.setAttribute('id', 'passo' + contadorEdit);
    novoInput.setAttribute('name', 'passo' + contadorEdit);
    novoInput.setAttribute('placeholder', 'Digite o passo...');

    // Cria input de imagem
    const novoInputImage = document.createElement('input');
    novoInputImage.setAttribute('type', 'file');
    novoInputImage.setAttribute('class', 'input-image');
    novoInputImage.setAttribute('id', 'image' + contadorEdit);
    novoInputImage.setAttribute('name', 'image' + contadorEdit);
    novoInputImage.setAttribute('onchange', 'previewImage(this)');
    novoInputImage.setAttribute('style', 'display: none;')

    // Crie o elemento do ícone
    const iconAnex = document.createElement('i');
    iconAnex.classList.add('fas', 'fa-image');
    // Cria Botão de input-image
    //<button type="button" id="input-image" class="input-image" onclick="clickInputFile()">teste</button>
    const novoBtnInputImage = document.createElement('button');
    novoBtnInputImage.textContent = ' Anexar Imagem ao Passo';
    novoBtnInputImage.setAttribute('type', 'button');
    novoBtnInputImage.setAttribute('id', 'input-image');
    novoBtnInputImage.setAttribute('class', 'btn-input-image');
    novoBtnInputImage.setAttribute('onclick', 'clickInputFile(' + contadorEdit +')');

    // Adicione o ícone ao botão
    novoBtnInputImage.prepend(iconAnex);

    // Cria um novo div para pré-visualização da imagem
    const divImagePreview = document.createElement('div');
    divImagePreview.setAttribute('id', 'image-preview' + contadorEdit);
    divImagePreview.classList.add('image-preview');

    // Criar icone de lixeira
    //<i class="fas fa-trash"></i>
    const iconDelete = document.createElement('i');
    iconDelete.classList.add('fas', 'fa-trash');

    // Cria um botão de deletar
    const btnDelete = document.createElement('button');
    btnDelete.textContent = ' Deletar Passo';
    btnDelete.setAttribute('type', 'button');
    btnDelete.setAttribute('onclick', 'deletarPasso(' + contadorEdit + ')');
    btnDelete.setAttribute('class', 'delete-step');

    // Adicione o ícone ao botão
    btnDelete.prepend(iconDelete);

    // Adiciona o label, input, div de pré-visualização e botão de deletar ao novo div
    novoDiv.appendChild(novoLabel);
    novoDiv.appendChild(novoInput);
    novoDiv.appendChild(novoInputImage);
    novoDiv.appendChild(novoBtnInputImage)
    novoDiv.appendChild(divImagePreview);
    novoDiv.appendChild(btnDelete);

    // Adiciona o novo div ao formulário antes do contêiner de botões
    const formulario = document.getElementById('form-include');
    const btnAdd = formulario.querySelector('.btns-form');

    if (formulario.contains(btnAdd)) {
        formulario.insertBefore(novoDiv, btnAdd);
    }

    contadorEdit++;
}

function deletarPasso(id) {
const passo = document.getElementById('step-container-' + id);
    passo.parentNode.removeChild(passo);
}

function previewImage(input) {
    const previewId = input.getAttribute('id').replace('image', 'image-preview');
    const preview = document.getElementById(previewId);
    preview.innerHTML = ''; // Limpa a visualização anterior

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = document.createElement('img');
            img.setAttribute('src', e.target.result);
            img.setAttribute('alt', 'Imagem de visualização');
            img.setAttribute('style', 'max-width: 500px;');
            preview.appendChild(img);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// configura botão para inserir imagem
function clickInputFile(id){
    if (id == 'image1') {
        document.getElementById(id).click()
    }else if(id == 'file-passo'){
        document.getElementById(id).click()
    }else {
        document.getElementById('image' + id).click()
    }
}
function clickEditFile(id){
    if (id.includes('image')) {
        document.getElementById(id).click()
    }else if(id == 'file-passo'){
        document.getElementById(id).click()
    }else {
        document.getElementById('image' + id).click()
    }
}

function removeImageAntiga(id) {
    const image = document.getElementById(id)
    if (image) {
        image.remove();
    }
}

// Muda o texto do anexo de arquivo .ZIP
document.getElementById('file-passo').addEventListener('change', function() {
    let fileName = this.files[0].name;
    if (fileName.length > 30) {
        fileName = fileName.substring(0, 27) + '...';
    }
    const button = document.querySelector('.btn-input-image');
    button.innerHTML = `<i class="fas fa-paperclip"></i> ${fileName}`;
});


/* document.getElementById('input-image').addEventListener('click', function() {
    document.getElementById('image' + contador).click();
});
*/
