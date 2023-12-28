import java.util.ArrayList;

public class Solve {
    private Node startNode;
    private Node goalNode;
    private Node currentNode;
    private Node[][] nodes;
    private boolean goalReached = false;
    private int step = 0;
    private ArrayList<Node> openList = new ArrayList<>();
    private ArrayList<Node> checkedList = new ArrayList<>();

    public Solve() {
        initComponents();
    }

    private void initComponents() {
        nodes = new Node[Map.ROWS][Map.COLS];
    }


    private void createPlaceNodes() {
        for(int i = 0; i < Map.ROWS; ++i) {
            for(int j = 0; j < Map.COLS; ++j) {
                Node node = new Node(i, j);
                this.nodes[i][j] = node;
                switch (Map.map[i][j]) {
                    case GamePlay.WALL:
                        this.setSoildNode(i, j);
                        break;
                    case GamePlay.PLAYER:
                        this.setStartNode(i, j);
                        break;
                    case GamePlay.GOAL:
                        this.setGoalNode(i, j);
                        break;
                    default:
                        break;
                }
            }
        }
    }

    private void setStartNode(int row, int col) {
        this.nodes[row][col].setAsStart();
        this.startNode =  this.nodes[row][col];
        this.currentNode = this.startNode;
    }

    private void setGoalNode(int row, int col) {
        this.nodes[row][col].setAsGoal();
        this.goalNode =  this.nodes[row][col];
    }

    private void setSoildNode(int row, int col) {
        this.nodes[row][col].setAsSoild();
    }

    private void setCostOnNodes() {
        for (int i = 0; i < Map.ROWS; ++i) {
            for (int j = 0; j < Map.COLS; ++j) {
                this.getCost(this.nodes[i][j]);
            }
        }
    }

    private void getCost(Node node) {
        // Tinh chi phi G (khoang cach giua node hien tai voi node bat dau)
        int xDistance = Math.abs(node.getCol() - startNode.getCol());
        int yDistance = Math.abs(node.getRow() - startNode.getRow());
        int gCost = xDistance + yDistance;
        node.setgCost(gCost);

        // Tinh chi phi H (khoang cach giua node hien tai voi node muc tieu)
        xDistance = Math.abs(node.getCol() - goalNode.getCol());
        yDistance = Math.abs(node.getRow() - goalNode.getRow());
        int hCost = xDistance + yDistance;
        node.sethCost(hCost);

        // Tinh chi phi F = G + H
        int fCost = gCost + hCost;
        node.setfCost(fCost);

        // Hien thi chi phi f va g tren  node
//        if(node != startNode && node != goalNode) {
//            node.setText("<html>F:" + node.getfCost() + "<br>G:" + node.getgCost() + "</html>");
//        }
    }

    public void autoSearch() {
        createPlaceNodes();
        setCostOnNodes();
        while(!goalReached && step++ < 1000) {
            int col = currentNode.getCol();
            int row = currentNode.getRow();

            currentNode.setAsChecked();
            checkedList.add(currentNode);
            openList.remove(currentNode);

            // open the up node
            if(row - 1 >= 0) {
                opendNode(this.nodes[row - 1][col]);
            }

            // open the left node
            if(col - 1 >= 0) {
                opendNode(this.nodes[row][col - 1]);
            }

            // open the down node
            if(row + 1 < Map.ROWS) {
                opendNode(this.nodes[row + 1][col]);
            }

            // open the right node
            if(col +  1 < Map.COLS) {
                opendNode(this.nodes[row][col + 1]);
            }

            // Tim node tot nhat
            int bestNodeIndex = -1;
            int bestNodefCost = Integer.MAX_VALUE;

            for(int i = 0; i < openList.size(); ++i) {
                if (openList.get(i).getfCost() < bestNodefCost) {
                    bestNodeIndex = i;
                    bestNodefCost = openList.get(i).getfCost();
                } else if (openList.get(i).getfCost() == bestNodefCost) {
                    if (openList.get(i).getgCost() < openList.get(bestNodeIndex).getgCost()) {
                        bestNodeIndex = i;
                    }
                }
            }

            // sau khi tim xong node tot nhat, tim node tot nhat ke tiep
            if(bestNodeIndex != -1) {
                this.currentNode = openList.get(bestNodeIndex);
                if(currentNode == goalNode) {
                    goalReached = true;
                    this.trackThePath();
                }
            }
        }
    }

    private void opendNode(Node node) {
        if(!node.isOpen() && !node.isChecked() && !node.isSolid()) {
            node.setAsOpen();
            node.setParent(currentNode);
            openList.add(node);
        }
    }

    private void trackThePath() {
        Node current = goalNode;

        while(current != startNode) {
            current = current.getParent();
            if(current != startNode) {
                int row = current.getRow();
                int col = current.getCol();
                Map.map[row][col] = GamePlay.VISITED;
            }
        }
    }
}