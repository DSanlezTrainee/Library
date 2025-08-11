<style>
    /* Garantir que os botões do carrossel sejam visíveis */
    .fixed-button {
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        opacity: 0.9;
    }

    .fixed-button:hover {
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
    }

    /* Estilos para o carrossel com transições laterais */
    .carousel-container {
        height: 600px;
        /* or any fixed height */
    }

    .carousel-slide {
        position: absolute;
        width: 100%;
        height: 100%;
        transition: transform 0.7s ease-in-out;
        opacity: 0;
        visibility: hidden;
        overflow-y: auto;
        padding: 1rem;
        box-sizing: border-box;
    }

    .carousel-slide.active {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    /* Para scrollbar personalizada */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .slide-next-enter {
        transform: translateX(100%);
    }

    .slide-prev-enter {
        transform: translateX(-100%);
    }

    .slide-next-leave {
        transform: translateX(-100%);
    }

    .slide-prev-leave {
        transform: translateX(100%);
    }
</style>

<div class="w-full max-w-2xl mx-auto py-8">
    <!-- Carrossel com abordagem direta -->
    <div x-data="{
        books: {{ json_encode($books) }},
        current: 0,
        direction: 'next', // 'next' ou 'prev' para controlar a direção da transição
        prev() { 
            this.direction = 'prev'; 
            this.current = this.current === 0 ? this.books.length - 1 : this.current - 1; 
        },
        next() { 
            this.direction = 'next'; 
            this.current = this.current === this.books.length - 1 ? 0 : this.current + 1; 
        },
        getSlideClass(idx) {
            if (this.current === idx) {
                return 'carousel-slide active';
            } else {
                if (this.direction === 'next') {
                    return idx < this.current ? 'carousel-slide slide-next-leave' : 'carousel-slide slide-next-enter';
                } else {
                    return idx < this.current ? 'carousel-slide slide-prev-leave' : 'carousel-slide slide-prev-enter';
                }
            }
        }
    }"
        class="relative overflow-hidden rounded-lg shadow-lg min-h-[500px] bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 carousel-container">

        <!-- Mensagem se não houver livros -->
        <div x-show="!books || books.length === 0" class="flex flex-col items-center justify-center p-6">
            <p class="text-red-500">Nenhum livro para mostrar</p>
        </div>

        <!-- Debug do livro atual -->
        <div class="absolute top-2 right-2 bg-black/70 text-white px-3 py-1 rounded-full text-xs z-40">
            <span x-text="'Livro ' + (current + 1) + ' de ' + books.length"></span>
        </div>

        <!-- Layout do carrossel com navegação -->
        <div class="flex items-center justify-between w-full h-full">
            <!-- Botão anterior embutido -->
            <div class="px-2 z-10">
                <button @click="prev"
                    class="bg-blue-600 text-black hover:bg-blue-700 rounded-full p-3 shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Conteúdo do livro -->
            <div class="flex-1 px-2 relative h-full">
                <template x-for="(book, idx) in books" :key="idx">
                    <div :class="getSlideClass(idx)"
                        class="flex md:flex-row flex-col items-center md:items-start justify-center p-4 h-full overflow-auto">
                        <div class="md:w-1/3 flex justify-center mb-6 md:mb-0">
                            <img :src="book.cover_image" alt="Capa do livro"
                                class="w-40 h-56 object-cover rounded-lg shadow-md hover:shadow-xl transition-shadow">
                        </div>
                        <div class="md:w-2/3 md:pl-8 overflow-y-auto max-h-[400px]">
                            <h2 class="text-xl font-bold mb-2" x-text="book.name"></h2>
                            <p class="text-gray-700 mb-3 text-sm leading-relaxed overflow-y-auto max-h-[250px] pr-2"
                                x-text="book.bibliography"></p>
                            <span
                                class="inline-block bg-gray-200 px-3 py-1 rounded-full text-sm font-semibold text-gray-700"
                                x-text="book.isbn"></span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Botão próximo embutido -->
            <div class="px-2">
                <button @click="next"
                    class="bg-blue-600 text-black hover:bg-blue-700 rounded-full p-3 shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Indicadores de paginação -->
        <div class="flex justify-center mt-6 space-x-3">
            <template x-for="(_, idx) in books" :key="idx">
                <button @click="current = idx" :class="{
                    'bg-blue-500 scale-110': current === idx, 
                    'bg-gray-300 hover:bg-gray-400': current !== idx
                }" class="w-3 h-3 rounded-full transition-all duration-200 transform"></button>
            </template>
        </div>
    </div>