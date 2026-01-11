/**
 * ============================================================================
 * APP AUTO - JavaScript do Dashboard
 * ============================================================================
 */

(function() {
    'use strict';

    // Elementos do DOM
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mainContent = document.getElementById('main-content');

    // ========================================================================
    // Menu Mobile
    // ========================================================================
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Fechar sidebar ao clicar fora (mobile)
    if (mainContent) {
        mainContent.addEventListener('click', function() {
            if (window.innerWidth <= 1024 && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    }

    // ========================================================================
    // Gráficos
    // ========================================================================
    
    // Verificar se Chart.js está disponível
    if (typeof Chart === 'undefined') {
        console.error('Chart.js não está carregado');
        return;
    }

    // Configuração padrão dos gráficos
    Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif';
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#718096';

    // Buscar dados dos gráficos
    async function loadChartData() {
        try {
            const response = await fetch('/cliente/dashboard/chart-data', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Erro ao carregar dados dos gráficos');
            }

            const result = await response.json();

            if (result.sucesso) {
                initCharts(result.data);
            } else {
                console.error('Erro:', result.mensagem);
                initChartsWithDefaultData();
            }
        } catch (error) {
            console.error('Erro ao carregar gráficos:', error);
            initChartsWithDefaultData();
        }
    }

    // Inicializar gráficos com dados
    function initCharts(data) {
        // Gráfico de Consumo
        const consumptionCanvas = document.getElementById('consumption-chart');
        if (consumptionCanvas) {
            new Chart(consumptionCanvas, {
                type: 'line',
                data: data.consumption,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderRadius: 8,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toFixed(1) + ' KM/L';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(1) + ' KM/L';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    elements: {
                        line: {
                            tension: 0.4
                        },
                        point: {
                            radius: 4,
                            hoverRadius: 6,
                            backgroundColor: '#ffffff',
                            borderWidth: 2
                        }
                    }
                }
            });
        }

        // Gráfico de Gastos
        const expensesCanvas = document.getElementById('expenses-chart');
        if (expensesCanvas) {
            new Chart(expensesCanvas, {
                type: 'bar',
                data: data.expenses,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderRadius: 8,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'R$ ' + context.parsed.y.toFixed(2).replace('.', ',');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toFixed(0);
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    elements: {
                        bar: {
                            borderRadius: 8,
                            borderSkipped: false
                        }
                    }
                }
            });
        }
    }

    // Inicializar gráficos com dados padrão (fallback)
    function initChartsWithDefaultData() {
        const defaultData = {
            consumption: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Consumo (KM/L)',
                    data: [12.5, 13.2, 11.8, 12.9, 13.5, 12.7],
                    backgroundColor: 'rgba(102, 126, 234, 0.2)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2,
                }]
            },
            expenses: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Gastos (R$)',
                    data: [450, 520, 380, 490, 510, 470],
                    backgroundColor: 'rgba(118, 75, 162, 0.2)',
                    borderColor: 'rgba(118, 75, 162, 1)',
                    borderWidth: 2,
                }]
            }
        };

        initCharts(defaultData);
    }

    // Carregar gráficos ao carregar a página
    loadChartData();

    // ========================================================================
    // Animações de Entrada
    // ========================================================================
    
    // Animar cards ao entrar na viewport
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                entry.target.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observar elementos animáveis
    const animatableElements = document.querySelectorAll('.stat-card, .chart-card, .alert, .latest-section');
    animatableElements.forEach(el => observer.observe(el));

    // ========================================================================
    // Atualização Automática (opcional)
    // ========================================================================
    
    // Atualizar dados a cada 5 minutos
    setInterval(function() {
        loadChartData();
    }, 5 * 60 * 1000);

    // ========================================================================
    // Utilitários
    // ========================================================================
    
    // Formatar moeda
    function formatCurrency(value) {
        return 'R$ ' + value.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Formatar número
    function formatNumber(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Tornar funções disponíveis globalmente (se necessário)
    window.dashboardUtils = {
        formatCurrency,
        formatNumber
    };

})();
