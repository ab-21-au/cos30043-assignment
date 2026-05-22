const API_KEY = import.meta.env.VITE_TMDB_API_KEY;
const BASE_URL = 'https://api.themoviedb.org/3';

export const GENRES = [
  { id: 28, name: 'Action' }, { id: 35, name: 'Comedy' }, { id: 18, name: 'Drama' },
  { id: 27, name: 'Horror' }, { id: 878, name: 'Sci-Fi' }, { id: 10749, name: 'Romance' },
  { id: 53, name: 'Thriller' }, { id: 12, name: 'Adventure' }, { id: 16, name: 'Animation' },
  { id: 80, name: 'Crime' }, { id: 14, name: 'Fantasy' }, { id: 9648, name: 'Mystery' },
  { id: 10752, name: 'War' }, { id: 37, name: 'Western' }
];

function shuffle(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
  return arr;
}

async function fetchAPI(endpoint) {
  const res = await fetch(`${BASE_URL}${endpoint}&api_key=${API_KEY}`);
  const data = await res.json();
  return data.results || [];
}

export const tmdb = {
  getRecentMovies: (page = 1) => fetchAPI(`/movie/now_playing?page=${page}`),
  
  async getRandomMoviesByGenre(genreId, limit = 20) { 
    const page = Math.floor(Math.random() * 20) + 1;
    const movies = await fetchAPI(`/discover/movie?with_genres=${genreId}&page=${page}`);
    return shuffle([...movies]).slice(0, limit);
  },
  

  async getAllGenresWithRandomMovies(limit = 20) {  
    const rows = [];
    for (const genre of GENRES) {
      rows.push({ genre, movies: await this.getRandomMoviesByGenre(genre.id, limit) });
    }
    return rows;
  },
  
  searchMovies: (query) => fetchAPI(`/search/movie?query=${encodeURIComponent(query)}`)
};