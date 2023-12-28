import javax.imageio.ImageIO;
import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;
import java.awt.image.BufferedImage;
import java.io.IOException;

public class GamePlay extends JFrame implements KeyListener, ActionListener {
    private static final int WIDTH = 680;
    private static final int HEIGHT = 640;
    public static final int VISITED = -1;
    public static final int EMPTY = 0;
    public static final int WALL = 1;
    public static final int PLAYER = 2;
    public static final int GOAL = 3;
    private static final int DELAY = 1000;
    private static final int SIZE = 30;
    private static final int LIMIT_MOVE = 10000;
    private int step = 0;
    private Timer timer;
    private Player player;
    private Goal goal;
    private Solve solve;
    private BufferedImage playerIcon;
    private BufferedImage wallIcon;
    private BufferedImage goalIcon;

    public GamePlay() {
        iconDrawingExample();
        initComponents();
    }

    public void iconDrawingExample() {
        try {
            // Load the player icon image (you should load your actual image here)
            playerIcon = ImageIO.read(getClass().getResourceAsStream(Icon.ICON_PLAYER));
            wallIcon = ImageIO.read(getClass().getResourceAsStream(Icon.ICON_WALL));
            goalIcon = ImageIO.read(getClass().getResourceAsStream(Icon.ICON_GOAL));
            //backgroundImage = ImageIO.read(getClass().getResourceAsStream(Icon.ICON_BACKGROUND));
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private void initComponents() {
        this.setTitle("Game Maze");
        this.setSize(WIDTH, HEIGHT);
        this.setLocationRelativeTo(null);
        this.addKeyListener(this);
        this.setLayout(null);
        this.setFocusable(true);
        this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        this.setVisible(true);

        timer = new Timer(DELAY, this);
        timer.start();

        player = new Player(-1, -1);
    }

    @Override
    public void paint(Graphics g) {
        super.paint(g);
        drawMap(g);
    }

    private void drawMap(Graphics g) {
        g.translate(50, 50);
        for(int i = 0; i < Map.ROWS; ++i) {
            for(int j = 0; j < Map.COLS; ++j) {
                createObject(i, j, Map.map, g);
            }
        }
    }

    private boolean isWin() {
        return player.getX() == goal.getX() && player.getY() == goal.getY();
    }

    private void createObject(int row, int col, int[][] map, Graphics g) {
        Color color = Color.YELLOW;
        switch (map[row][col]) {
            case WALL:
                //color = Color.BLUE;
                g.drawImage(wallIcon, SIZE * col, SIZE * row, SIZE, SIZE, null);
                break;
            case PLAYER:
                //color = Color.GREEN;
                player.setX(row);
                player.setY(col);
                g.drawImage(playerIcon, SIZE * col, SIZE * row, SIZE, SIZE, null);
                break;
            case GOAL:
                //color = Color.RED;
                if(goal == null) {
                    goal = new Goal(row, col);
                }
                g.drawImage(goalIcon, SIZE * col, SIZE * row, SIZE, SIZE, null);
                break;
            case VISITED:
                color = Color.GRAY;
                break;
            case EMPTY:
                color = Color.WHITE;
                break;

            default:
                break;
        }
        if(color != Color.YELLOW) {
            g.setColor(color);
            g.fillRect(SIZE * col, SIZE * row, SIZE, SIZE);
            g.setColor(Color.BLACK);
            g.drawRect(SIZE * col, SIZE * row, SIZE, SIZE);
        }
    }

    private void move() {
        int currentX = player.getX();
        int currentY = player.getY();

        // check path left
        if (currentY - 1 >= 0 && (Map.map[currentX][currentY - 1] == VISITED || Map.map[currentX][currentY - 1] == GOAL)) {
            player.setY(currentY - 1);

            Map.map[currentX][currentY] = EMPTY;
            //repaint(SIZE * currentY, SIZE * currentX, SIZE, SIZE);

            Map.map[player.getX()][player.getY()] = PLAYER;
            // repaint(SIZE * player.getY(),SIZE * player.getX(), SIZE, SIZE);
            repaint();
        }

        // check path right
        else if (currentY + 1 < Map.COLS && (Map.map[currentX][currentY + 1] == VISITED || Map.map[currentX][currentY - 1] == GOAL)) {
            player.setY(currentY + 1);

            Map.map[currentX][currentY] = EMPTY;
            // repaint(SIZE * currentY, SIZE * currentX, SIZE, SIZE);

            Map.map[player.getX()][player.getY()] = PLAYER;
            //repaint(SIZE * player.getY(),SIZE * player.getX(), SIZE, SIZE);
            repaint();
        }

        // check path down
        else if (currentX - 1 >= 0 && (Map.map[currentX - 1][currentY] == VISITED || Map.map[currentX][currentY - 1] == GOAL)) {
            player.setX(currentX - 1);

            Map.map[currentX][currentY] = EMPTY;
            // repaint(SIZE * currentY, SIZE * currentX, SIZE, SIZE);

            Map.map[player.getX()][player.getY()] = PLAYER;
            //repaint(SIZE * player.getY(),SIZE * player.getX(), SIZE, SIZE);
            repaint();
        }

        // check path up
        else if (currentX + 1 < Map.ROWS && (Map.map[currentX + 1][currentY] == VISITED || Map.map[currentX][currentY - 1] == GOAL)) {
            player.setX(currentX + 1);

            Map.map[currentX][currentY] = EMPTY;
            //repaint(SIZE * currentY, SIZE * currentX, SIZE, SIZE);

            Map.map[player.getX()][player.getY()] = PLAYER;
            //repaint(SIZE * player.getY(),SIZE * player.getX(), SIZE, SIZE);
            repaint();
        }
    }

    private void autoMove() {
        if(solve == null) {
            solve = new Solve();
            solve.autoSearch();
        }

        move();
        if(step >= LIMIT_MOVE) {
            System.out.println("Khong co duong den dich");
        }
        else if(isWin()) {
            System.out.println("Da tim duoc dich");
            timer.stop();
        }

    }

    @Override
    public void keyTyped(KeyEvent e) {

    }

    @Override
    public void keyPressed(KeyEvent e) {
        int code = e.getKeyCode();
        switch (code) {
            case KeyEvent.VK_ENTER:
                autoMove();
                break;
            default:
                break;
        }
    }

    @Override
    public void keyReleased(KeyEvent e) {

    }

    @Override
    public void actionPerformed(ActionEvent e) {
        autoMove();
    }
}