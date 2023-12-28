public class Player {
    private int x;
    private int y;

    public Player(int x, int y) {
        this.x = x;
        this.y = y;
    }

    public int getX() {
        return x;
    }

    public void setX(int x) {
        if(x >= 0 && x < Map.ROWS) {
            this.x = x;
        }
    }

    public int getY() {
        return y;
    }

    public void setY(int y) {
        if(y >= 0 && y < Map.COLS) {
            this.y = y;
        }
    }

    public void moveLeft() {
        y = (y - 1 >= 0 ? --y : y);
    }

    public void moveRight() {
        y = (y + 1 < Map.COLS ? ++y : y);
    }

    public void moveUp() {
        x = (x - 1 >= 0 ? --x : x);
    }

    public void moveDown() {
        x = (x + 1 < Map.ROWS ? ++x : x);
    }
}