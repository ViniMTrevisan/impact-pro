document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const response = await fetch('../php/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${email}&senha=${senha}`
    });
    const result = await response.json();
    if (result.success) {
        window.location.href = 'opportunities.php';
    } else {
        alert('Erro: ' + result.mensagem);
    }
});

document.getElementById('registerForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(document.getElementById('registerForm'));
    const response = await fetch('../php/register.php', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();
    if (result.success) {
        window.location.href = 'opportunities.php';
    } else {
        alert('Erro: ' + result.mensagem);
    }
});

async function buscarUsuarios(event) {
    event.preventDefault();
    const termo = document.getElementById('searchInput').value;
    const tipo = document.getElementById('searchType').value;
    const localizacao = document.getElementById('searchLocation').value;
    const area_atuacao = document.getElementById('searchCategory').value;
    try {
        const response = await fetch('../php/search.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `termo=${termo}&tipo=${tipo}&localizacao=${localizacao}&area_atuacao=${area_atuacao}`
        });
        if (!response.ok) {
            throw new Error('Erro na requisição: ' + response.status);
        }
        const resultados = await response.json();
        const resultadosDiv = document.getElementById('searchResults');
        if (resultados.length > 0) {
            resultadosDiv.innerHTML = resultados.map(usuario => `
                <p>${usuario.nome} (${usuario.tipo}) - ${usuario.localizacao}, ${usuario.area_atuacao}</p>
            `).join('');
        } else {
            resultadosDiv.innerHTML = '<p>Nenhum resultado encontrado.</p>';
        }
        carregarUsuariosChat();
    } catch (error) {
        console.error('Erro ao buscar usuários:', error);
        alert('Falha ao realizar a busca. Tente novamente.');
    }
}

async function carregarUsuariosChat() {
    try {
        const response = await fetch('../php/search.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'termo=&tipo=todos'
        });
        const usuarios = await response.json();
        const select = document.getElementById('chatUser');
        select.innerHTML = '<option value="">Selecione um usuário</option>';
        usuarios.forEach(usuario => {
            select.innerHTML += `<option value="${usuario.id}">${usuario.nome} (${usuario.tipo})</option>`;
        });
    } catch (error) {
        console.error('Erro ao carregar usuários do chat:', error);
    }
}

async function carregarChat() {
    const destinatarioId = document.getElementById('chatUser').value;
    if (!destinatarioId) return;
    try {
        const response = await fetch('../php/chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `acao=carregar&destinatario_id=${destinatarioId}`
        });
        const mensagens = await response.json();
        const chatDiv = document.getElementById('chatMessages');
        chatDiv.innerHTML = mensagens.map(msg => `
            <p><b>${msg.nome_remetente}:</b> ${msg.mensagem} (${msg.enviado_em})</p>
        `).join('');
    } catch (error) {
        console.error('Erro ao carregar chat:', error);
    }
}

async function enviarMensagem() {
    const destinatarioId = document.getElementById('chatUser').value;
    const mensagem = document.getElementById('messageInput').value;
    if (!destinatarioId || !mensagem) return;
    try {
        const response = await fetch('../php/chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `acao=enviar&destinatario_id=${destinatarioId}&mensagem=${mensagem}`
        });
        const result = await response.json();
        if (result.success) {
            document.getElementById('messageInput').value = '';
            carregarChat();
        } else {
            alert('Erro: ' + result.mensagem);
        }
    } catch (error) {
        console.error('Erro ao enviar mensagem:', error);
        alert('Falha ao enviar mensagem. Tente novamente.');
    }
}

document.getElementById('opportunityForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(document.getElementById('opportunityForm'));
    try {
        const response = await fetch('../php/oportunidades.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            carregarOportunidades();
        } else {
            alert('Erro: ' + result.mensagem);
        }
    } catch (error) {
        console.error('Erro ao criar oportunidade:', error);
        alert('Falha ao criar oportunidade. Tente novamente.');
    }
});

async function carregarOportunidades() {
    try {
        const response = await fetch('../php/oportunidades.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'acao=carregar'
        });
        const oportunidades = await response.json();
        const listDiv = document.getElementById('opportunitiesList');
        listDiv.innerHTML = oportunidades.map(opp => `
            <div>
                <h3>${opp.titulo}</h3>
                <p>${opp.descricao}</p>
                <p>Local: ${opp.localizacao} | Área: ${opp.area_atuacao}</p>
                <button onclick="candidatarOportunidade(${opp.id})">Candidatar-se</button>
            </div>
        `).join('');
    } catch (error) {
        console.error('Erro ao carregar oportunidades:', error);
    }
}

async function candidatarOportunidade(oportunidadeId) {
    try {
        const response = await fetch('../php/oportunidades.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `acao=candidatar&oportunidade_id=${oportunidadeId}`
        });
        const result = await response.json();
        if (result.success) {
            alert('Candidatura enviada com sucesso!');
            carregarOportunidades();
        } else {
            alert('Erro: ' + result.mensagem);
        }
    } catch (error) {
        console.error('Erro ao candidatar-se:', error);
        alert('Falha ao candidatar-se. Tente novamente.');
    }
}

if (document.getElementById('searchResults')) carregarUsuariosChat();
if (document.getElementById('opportunitiesList')) carregarOportunidades();