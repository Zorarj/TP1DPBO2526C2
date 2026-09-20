public class Main {
    public static void main(String[] args) {
        Film f = new Film();

        f.addFilm(1, "Avengers", "Action", 120000);
        f.showAllFilm();
        f.updateFilm(1, "Obsession", "Horror", 500000);
        f.showAllFilm();
        f.removeFilm(1);
        f.showAllFilm();
        
    }
}
