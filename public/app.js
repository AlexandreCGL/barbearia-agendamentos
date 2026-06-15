const API_URL = 'http://localhost/barbearia';

// ===== CARREGAR BARBEIROS =====
async function carregarBarbeiros() {
    const resposta = await fetch(`${API_URL}/barbeiros`);
    const barbeiros = await resposta.json();

    const select = document.getElementById('barbeiro');
    select.innerHTML = '<option value="">Selecione um barbeiro</option>';

    barbeiros.forEach(barbeiro => {
        const option = document.createElement('option');
        option.value = barbeiro.id;
        option.textContent = barbeiro.nome;
        select.appendChild(option);
    });
}

// ===== CARREGAR HORÁRIOS DISPONÍVEIS =====
async function carregarHorarios() {
    const barbeiroId = document.getElementById('barbeiro').value;
    const data = document.getElementById('data').value;

    const selectHorario = document.getElementById('horario');

    if (!barbeiroId || !data) {
        selectHorario.innerHTML = '<option value="">Selecione barbeiro e data primeiro</option>';
        return;
    }

    selectHorario.innerHTML = '<option value="">Carregando horários...</option>';

    const resposta = await fetch(`${API_URL}/agendamentos?barbeiro_id=${barbeiroId}&data=${data}`);
    const horarios = await resposta.json();

    if (horarios.length === 0) {
        selectHorario.innerHTML = '<option value="">Nenhum horário disponível</option>';
        return;
    }

    selectHorario.innerHTML = '<option value="">Selecione um horário</option>';
    horarios.forEach(hora => {
        const option = document.createElement('option');
        option.value = hora;
        option.textContent = hora;
        selectHorario.appendChild(option);
    });
}

// ===== CRIAR AGENDAMENTO =====
async function criarAgendamento() {
    const nome = document.getElementById('nome').value;
    const telefone = document.getElementById('telefone').value;
    const barbeiroId = document.getElementById('barbeiro').value;
    const data = document.getElementById('data').value;
    const hora = document.getElementById('horario').value;

    const mensagem = document.getElementById('mensagem');
    mensagem.className = '';
    mensagem.style.display = 'none';

    if (!nome || !telefone || !barbeiroId || !data || !hora) {
        mensagem.textContent = '⚠️ Preencha todos os campos!';
        mensagem.className = 'erro';
        return;
    }

    const resposta = await fetch(`${API_URL}/agendamentos`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nome, telefone, barbeiro_id: barbeiroId, data, hora })
    });

    const resultado = await resposta.json();

    if (resposta.ok) {
        mensagem.textContent = '✅ Agendamento realizado com sucesso!';
        mensagem.className = 'sucesso';
        document.getElementById('nome').value = '';
        document.getElementById('telefone').value = '';
        document.getElementById('data').value = '';
        carregarHorarios();
        carregarAgendamentos();
    } else {
        mensagem.textContent = '❌ ' + (resultado.erro || 'Erro ao agendar.');
        mensagem.className = 'erro';
    }
}

// ===== LISTAR AGENDAMENTOS =====
async function carregarAgendamentos() {
    const barbeiroId = document.getElementById('barbeiro').value;

    if (!barbeiroId) return;

    const resposta = await fetch(`${API_URL}/agendamentos?barbeiro_id=${barbeiroId}`);
    const agendamentos = await resposta.json();

    const lista = document.getElementById('lista-agendamentos');

    if (agendamentos.length === 0) {
        lista.innerHTML = '<p>Nenhum agendamento para hoje.</p>';
        return;
    }

    lista.innerHTML = '';
    agendamentos.forEach(ag => {
        const card = document.createElement('div');
        card.className = 'agendamento-card';
        card.innerHTML = `
            <div class="cliente-nome">${ag.nome_cliente}</div>
            <div class="agendamento-info">
                <span>📅 ${ag.data_agendamento}</span>
                <span>🕐 ${ag.hora_agendamento}</span>
            </div>
            <button class="btn-cancelar" onclick="cancelarAgendamento(${ag.id})">
                Cancelar
            </button>
        `;
        lista.appendChild(card);
    });
}

// ===== CANCELAR AGENDAMENTO =====
async function cancelarAgendamento(id) {
    if (!confirm('Tem certeza que deseja cancelar este agendamento?')) return;

    const resposta = await fetch(`${API_URL}/agendamentos/${id}/cancelar`, {
        method: 'POST'
    });

    const resultado = await resposta.json();

    if (resposta.ok) {
        carregarAgendamentos();
    } else {
        alert('Erro ao cancelar: ' + resultado.erro);
    }
}

// ===== EVENTOS =====
document.getElementById('barbeiro').addEventListener('change', () => {
    carregarHorarios();
    carregarAgendamentos();
});

document.getElementById('data').addEventListener('change', carregarHorarios);
document.getElementById('btn-agendar').addEventListener('click', criarAgendamento);

// ===== INICIALIZAR =====
carregarBarbeiros();