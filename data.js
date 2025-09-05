// Sample movie data
const movies = [
    {
        id: 1,
        title: "Avengers: Endgame",
        genre: "action",
        duration: 181,
        price: 120000,
        description: "Cuộc chiến cuối cùng của các siêu anh hùng",
        poster: "🦸‍♂️",
        showtimes: ["10:00", "13:30", "16:45", "20:00"]
    },
    {
        id: 2,
        title: "Parasite",
        genre: "drama",
        duration: 132,
        price: 100000,
        description: "Bộ phim đoạt giải Oscar xuất sắc",
        poster: "🎭",
        showtimes: ["11:15", "14:30", "17:45", "21:00"]
    },
    {
        id: 3,
        title: "The Conjuring 3",
        genre: "horror",
        duration: 112,
        price: 110000,
        description: "Phim kinh dị đầy ám ảnh",
        poster: "👻",
        showtimes: ["12:00", "15:15", "18:30", "21:45"]
    },
    {
        id: 4,
        title: "Fast & Furious 9",
        genre: "action",
        duration: 143,
        price: 115000,
        description: "Tốc độ và đam mê phần 9",
        poster: "🏎️",
        showtimes: ["09:30", "12:45", "16:00", "19:15"]
    },
    {
        id: 5,
        title: "Spider-Man: No Way Home",
        genre: "action",
        duration: 148,
        price: 125000,
        description: "Người nhện đa vũ trụ",
        poster: "🕷️",
        showtimes: ["10:30", "14:00", "17:30", "21:00"]
    },
    {
        id: 6,
        title: "Dune",
        genre: "drama",
        duration: 155,
        price: 118000,
        description: "Hành tinh cát bí ẩn",
        poster: "🏜️",
        showtimes: ["11:00", "14:45", "18:15", "21:30"]
    }
];

// Initialize booking history from localStorage
let bookingHistory = JSON.parse(localStorage.getItem('bookingHistory')) || [];

// Save booking history to localStorage
function saveBookingHistory() {
    localStorage.setItem('bookingHistory', JSON.stringify(bookingHistory));
}

// Get movie by ID
function getMovieById(id) {
    return movies.find(movie => movie.id == id);
}