<style>
    /* Garantir que os botões do carrossel sejam visíveis */
    .fixed-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        opacity: 0.8;
        border: 2px solid rgba(255, 255, 255, 0.4);
    }

    .fixed-button:hover {
        opacity: 1;
        transform: scale(1.1);
        border: 2px solid rgba(255, 255, 255, 0.8);
    }

    /* Estilos para o carrossel com transições laterais */
    .carousel-container {
        height: 480px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border-radius: 1rem;
        background: linear-gradient(145deg, #f8fafc, #ffffff);
        overflow: hidden;
        position: relative;
    }

    .carousel-slide {
        position: absolute;
        width: 100%;
        height: 100%;
        transition: transform 0.7s ease-in-out;
        opacity: 0;
        visibility: hidden;
        box-sizing: border-box;
        background: linear-gradient(to right, #f8f8f8, #ffffff);
        display: flex;
        align-items: center;
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

<div class="w-full max-w-3xl mx-auto py-8">
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
        class="relative overflow-hidden rounded-lg shadow-lg min-h-[400px] bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 carousel-container">

        <!-- Mensagem se não houver livros -->
        <div x-show="!books || books.length === 0" class="flex flex-col items-center justify-center p-6">
            <p class="text-red-500">No books</p>
        </div>

        <!-- Indicador do livro atual -->
        <div
            class="absolute top-3 right-3 bg-gradient-to-r from-white-500 to-blue-700 text-black px-4 py-1 rounded-full text-xs font-medium z-40 shadow-md">
            <span x-text="'Book ' + (current + 1) + ' de ' + books.length"></span>
        </div>

        <!-- Layout do carrossel com navegação -->
        <div class="flex items-center justify-between w-full h-full">
            <!-- Botão anterior embutido -->
            <div class="px-2 z-10">
                <button @click="prev"
                    class="fixed-button bg-white text-blue-600 hover:bg-blue-600 hover:text-white rounded-full p-3 shadow-lg transition-all duration-300 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <!-- Conteúdo do livro -->
            <div class="flex-1 px-4 relative h-full">
                <template x-for="(book, idx) in books" :key="idx">
                    <div :class="getSlideClass(idx)"
                        class="flex md:flex-row flex-col items-start justify-between p-6 h-full">
                        <div class="md:w-1/3 flex justify-center items-start mb-6 md:mb-0">
                            <img :src="book.cover_image" :alt="'Capa do livro ' + book.name"
                                class="w-48 h-64 object-cover rounded-lg shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 border-2 border-gray-100">
                        </div>
                        <div class="md:w-2/3 md:pl-8 flex flex-col justify-start">
                            <h2 class="text-2xl font-bold mb-2" x-text="book.name"></h2>
                            <div class="flex flex-wrap gap-1 mb-3">
                                <span class="text-black-600 font-medium" x-text="'By: ' + book.authors"></span>
                                <span class="text-gray-500">•</span>
                                <span class="text-gray-600" x-text="'Ed.: ' + book.publisher"></span>
                            </div>

                            <div class="max-h-40 overflow-y-auto mb-4 pr-2">
                                <p class="text-gray-700 text-base leading-relaxed" x-text="book.bibliography"></p>
                            </div>
                            <div class="max-h-40 overflow-y-auto mb-4 pr-2">
                                <span
                                    class="inline-block bg-gray-200 px-3 py-1 rounded-full text-sm font-semibold text-gray-700"
                                    x-text="'ISBN: ' + book.isbn"></span>
                            </div>

                        </div>
                    </div>
                </template>
            </div>

            <!-- Botão próximo embutido -->
            <div class="px-2">
                <button @click="next"
                    class="fixed-button bg-white text-blue-600 hover:bg-blue-600 hover:text-white rounded-full p-3 shadow-lg transition-all duration-200 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Indicadores de paginação -->
        <div class="flex justify-center mt-4 space-x-3 absolute bottom-4 left-0 right-0">
            <template x-for="(_, idx) in books" :key="idx">
                <button @click="current = idx" :class="{
                    'bg-blue-600 w-4 h-4 scale-110': current === idx, 
                    'bg-gray-300 hover:bg-gray-400 w-3 h-3': current !== idx
                }" class="rounded-full transition-all duration-300 transform shadow"></button>
            </template>
        </div>
    </div>