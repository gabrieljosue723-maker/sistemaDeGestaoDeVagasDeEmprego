/**
 * hoverRipple: adiciona um pequeno efeito de "onda" a qualquer elemento
 * com a classe `.btn-ripple`, disparado apenas quando o rato ENTRA no
 * elemento (mouseenter) — nunca em clique ou foco.
 *
 * Funciona com conteúdo renderizado no carregamento da página e também
 * com elementos adicionados dinamicamente depois (ex: paginação, modais),
 * graças ao MutationObserver.
 */
function anexarRipple(botao) {
    if (botao.dataset.rippleAnexado === '1') {
        return;
    }
    botao.dataset.rippleAnexado = '1';

    botao.addEventListener('mouseenter', (evento) => {
        const rect = botao.getBoundingClientRect();
        const tamanho = Math.max(rect.width, rect.height) * 1.8;

        const onda = document.createElement('span');
        onda.className = 'ripple-effect';
        onda.style.width = `${tamanho}px`;
        onda.style.height = `${tamanho}px`;
        onda.style.left = `${evento.clientX - rect.left - tamanho / 2}px`;
        onda.style.top = `${evento.clientY - rect.top - tamanho / 2}px`;

        botao.appendChild(onda);

        onda.addEventListener('animationend', () => onda.remove());
    });
}

function inicializarRipples(raiz = document) {
    raiz.querySelectorAll('.btn-ripple').forEach(anexarRipple);
}

document.addEventListener('DOMContentLoaded', () => inicializarRipples());

const observador = new MutationObserver((mutacoes) => {
    mutacoes.forEach((mutacao) => {
        mutacao.addedNodes.forEach((no) => {
            if (no.nodeType !== 1) return;
            if (no.classList && no.classList.contains('btn-ripple')) {
                anexarRipple(no);
            }
            inicializarRipples(no);
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    observador.observe(document.body, { childList: true, subtree: true });
});
