import java.awt.*;
import java.awt.event.ActionEvent;

public class Node {
    private Node parent;
    private int col;
    private int row;
    private int gCost;
    private int hCost;
    private int fCost;
    private boolean start;
    private boolean goal;
    private boolean solid;
    private boolean open;
    private boolean checked;

    public Node(int row, int col) {
        initComponents(row, col);
    }

    private void initComponents(int row, int col) {
        this.row = row;
        this.col = col;
    }

    public void setAsStart() {
        this.start = true;
    }

    public void setAsGoal() {
        this.goal = true;
    }

    public void setAsSoild() {

        this.solid = true;
    }

    public void setAsOpen() {
        open = true;
    }

    public void setAsChecked() {
        checked = true;
    }

    public void setAsPath() {
    }

    public Node getParent() {
        return parent;
    }

    public void setParent(Node parent) {
        this.parent = parent;
    }

    public int getCol() {
        return col;
    }

    public void setCol(int col) {
        this.col = col;
    }

    public int getRow() {
        return row;
    }

    public void setRow(int row) {
        this.row = row;
    }

    public int getgCost() {
        return gCost;
    }

    public void setgCost(int gCost) {
        this.gCost = gCost;
    }

    public int gethCost() {
        return hCost;
    }

    public void sethCost(int hCost) {
        this.hCost = hCost;
    }

    public int getfCost() {
        return fCost;
    }

    public void setfCost(int fCost) {
        this.fCost = fCost;
    }

    public boolean isStart() {
        return start;
    }

    public void setStart(boolean start) {
        this.start = start;
    }

    public boolean isGoal() {
        return goal;
    }

    public void setGoal(boolean goal) {
        this.goal = goal;
    }

    public boolean isSolid() {
        return solid;
    }

    public void setSolid(boolean solid) {
        this.solid = solid;
    }

    public boolean isOpen() {
        return open;
    }

    public void setOpen(boolean open) {
        this.open = open;
    }

    public boolean isChecked() {
        return checked;
    }

    public void setChecked(boolean checked) {
        this.checked = checked;
    }
}

