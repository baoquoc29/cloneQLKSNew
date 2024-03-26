//
// Source code recreated from a .class file by IntelliJ IDEA
// (powered by FernFlower decompiler)
//

package org.example;

import java.time.Duration;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.Assertions;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.openqa.selenium.Alert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.edge.EdgeDriver;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

public class TestWeb {
    private static final String KEY = "webdriver.chrome.driver";
    private static final String VALUE = "D:\\chrome driver\\chromedriver-win64\\chromedriver.exe";
    private static final String URL = "http://localhost/web_hotel_management1/login.php";
    private static final String TITLE = "";
    private WebDriver webDriver;

    public TestShopeeLogin() {
    }

    @BeforeEach
    public void setUp() throws Exception {
        System.setProperty("webdriver.chrome.driver", "D:\\chrome driver\\chromedriver-win64\\chromedriver.exe");
        this.webDriver = new EdgeDriver();
        this.webDriver.get("http://localhost/web_hotel_management1/login.php");
    }

    @Test
    public void testLogin1() throws InterruptedException {
        if (this.webDriver != null) {
            System.out.println("Test dang nhap");
            WebElement usernameInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[1]/input"));
            WebElement passwordInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[2]/input"));
            WebElement loginButton = this.webDriver.findElement(By.xpath("/html/body/div/form/button"));
            if (usernameInput.isDisplayed()) {
                usernameInput.sendKeys(new CharSequence[]{"quocne123"});
            }

            if (passwordInput.isDisplayed()) {
                passwordInput.sendKeys(new CharSequence[]{"123456"});
            }

            loginButton.click();

            try {
                Thread.sleep(10000L);
            } catch (Exception var5) {
                Logger.getLogger(TestShopeeLogin.class.getName()).log(Level.SEVERE, (String)null, var5);
            }

            Assertions.assertTrue(this.webDriver.findElement(By.xpath("//*[@id=\"navbarDropdown\"]")) != null);
        }
    }

    @Test
    public void testLogin2() throws InterruptedException {
        if (this.webDriver != null) {
            System.out.println("Test dang nhap");
            WebElement usernameInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[1]/input"));
            WebElement passwordInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[2]/input"));
            WebElement loginButton = this.webDriver.findElement(By.xpath("/html/body/div/form/button"));
            if (usernameInput.isDisplayed()) {
                usernameInput.sendKeys(new CharSequence[]{"0123456789aaa"});
            }

            if (passwordInput.isDisplayed()) {
                passwordInput.sendKeys(new CharSequence[]{"Haiminh@042003!"});
            }

            loginButton.click();

            try {
                Thread.sleep(10000L);
            } catch (Exception var6) {
                Logger.getLogger(TestShopeeLogin.class.getName()).log(Level.SEVERE, (String)null, var6);
            }

            Alert alert = this.webDriver.switchTo().alert();
            String alertText = alert.getText();
            System.out.println(alertText);
            alert.accept();
            Assertions.assertEquals(alertText, "Tên tài khoản hoặc mật khẩu không chính xác");
        }
    }

    @Test
    public void testLogin3() throws InterruptedException {
        if (this.webDriver != null) {
            System.out.println("Test dang nhap");
            WebElement usernameInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[1]/input"));
            WebElement passwordInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[2]/input"));
            WebElement loginButton = this.webDriver.findElement(By.xpath("/html/body/div/form/button"));
            if (usernameInput.isDisplayed()) {
                usernameInput.sendKeys(new CharSequence[]{"0394621961"});
            }

            if (passwordInput.isDisplayed()) {
                passwordInput.sendKeys(new CharSequence[]{"Haiminh@042003"});
            }

            loginButton.click();

            try {
                Thread.sleep(10000L);
            } catch (Exception var6) {
                Logger.getLogger(TestShopeeLogin.class.getName()).log(Level.SEVERE, (String)null, var6);
            }

            Alert alert = this.webDriver.switchTo().alert();
            String alertText = alert.getText();
            System.out.println(alertText);
            alert.accept();
            Assertions.assertEquals(alertText, "Tên tài khoản hoặc mật khẩu không chính xác");
        }
    }

    @Test
    public void testLogin4() throws InterruptedException {
        if (this.webDriver != null) {
            System.out.println("Test dang nhap");
            WebElement usernameInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[1]/input"));
            WebElement passwordInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[2]/input"));
            WebElement loginButton = this.webDriver.findElement(By.xpath("/html/body/div/form/button"));
            if (usernameInput.isDisplayed()) {
                usernameInput.sendKeys(new CharSequence[]{""});
            }

            if (passwordInput.isDisplayed()) {
                passwordInput.sendKeys(new CharSequence[]{"Haiminh@042003!"});
            }

            try {
                Thread.sleep(10000L);
            } catch (Exception var5) {
                Logger.getLogger(TestShopeeLogin.class.getName()).log(Level.SEVERE, (String)null, var5);
            }

            Assertions.assertTrue(loginButton.isEnabled());
        }
    }

    @Test
    public void testLogin5() throws InterruptedException {
        if (this.webDriver != null) {
            System.out.println("Test dang nhap");
            WebElement usernameInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[1]/input"));
            WebElement passwordInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[2]/input"));
            WebElement loginButton = this.webDriver.findElement(By.xpath("/html/body/div/form/button"));
            loginButton.click();
            if (usernameInput.isDisplayed()) {
                usernameInput.sendKeys(new CharSequence[]{"0394621961"});
            }

            if (passwordInput.isDisplayed()) {
                passwordInput.sendKeys(new CharSequence[]{""});
            }

            try {
                Thread.sleep(10000L);
            } catch (Exception var5) {
                Logger.getLogger(TestShopeeLogin.class.getName()).log(Level.SEVERE, (String)null, var5);
            }

            Assertions.assertTrue(loginButton.isEnabled());
        }
    }

    @Test
    public void testLogin6() throws InterruptedException {
        if (this.webDriver != null) {
            System.out.println("Test dang nhap");
            WebElement usernameInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[1]/input"));
            WebElement passwordInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[2]/input"));
            WebElement loginButton = this.webDriver.findElement(By.xpath("/html/body/div/form/button"));
            loginButton.click();
            if (usernameInput.isDisplayed()) {
                usernameInput.sendKeys(new CharSequence[]{""});
            }

            if (passwordInput.isDisplayed()) {
                passwordInput.sendKeys(new CharSequence[]{""});
            }

            try {
                Thread.sleep(10000L);
            } catch (Exception var5) {
                Logger.getLogger(TestShopeeLogin.class.getName()).log(Level.SEVERE, (String)null, var5);
            }

            Assertions.assertTrue(loginButton.isEnabled());
        }
    }

    @Test
    public void testLogin7() throws InterruptedException {
        if (this.webDriver != null) {
            System.out.println("Test dang nhap");
            WebElement usernameInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[1]/input"));
            WebElement passwordInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[2]/input"));
            WebElement loginButton = this.webDriver.findElement(By.xpath("/html/body/div/form/button"));
            loginButton.click();
            if (usernameInput.isDisplayed()) {
                usernameInput.sendKeys(new CharSequence[]{"0394621962"});
            }

            if (passwordInput.isDisplayed()) {
                passwordInput.sendKeys(new CharSequence[]{"Haiminh@042003"});
            }

            loginButton.click();

            try {
                Thread.sleep(10000L);
            } catch (Exception var6) {
                Logger.getLogger(TestShopeeLogin.class.getName()).log(Level.SEVERE, (String)null, var6);
            }

            Alert alert = this.webDriver.switchTo().alert();
            String alertText = alert.getText();
            System.out.println(alertText);
            alert.accept();
            Assertions.assertEquals(alertText, "Tên tài khoản hoặc mật khẩu không chính xác");
        }
    }

    @Test
    public void testSearch1() throws InterruptedException {
        if (this.webDriver != null) {
            this.LoginSucces();
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(5L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            WebElement inputCheckIn = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[1]")));
            WebElement inputCheckOut = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[2]")));
            if (inputCheckIn.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckIn, "12/24/2023");
            }

            if (inputCheckOut.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckOut, "12/29/2023");
            }

            WebElement buttonSearch = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/button")));
            WebElement countPerson = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[3]/div/input")));
            countPerson.clear();
            countPerson.sendKeys(new CharSequence[]{"4"});
            WebElement min = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[1]/input")));
            min.clear();
            min.sendKeys(new CharSequence[]{"0"});
            WebElement max = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[3]/input")));
            max.clear();
            max.sendKeys(new CharSequence[]{"10000000"});
            buttonSearch.click();

            try {
                Thread.sleep(5000L);
            } catch (InterruptedException var10) {
                var10.printStackTrace();
            }

            System.out.println("Tìm Kiếm Thành Công");
        }
    }

    @Test
    public void testSearch2() throws InterruptedException {
        if (this.webDriver != null) {
            this.LoginSucces();
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(5L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            WebElement inputCheckIn = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[1]")));
            WebElement inputCheckOut = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[2]")));
            if (inputCheckIn.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckIn, "01/01/2024");
            }

            if (inputCheckOut.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckOut, "12/25/2023");
            }

            WebElement buttonSearch = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/button")));
            WebElement countPerson = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[3]/div/input")));
            countPerson.clear();
            countPerson.sendKeys(new CharSequence[]{"4"});
            WebElement min = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[1]/input")));
            min.clear();
            min.sendKeys(new CharSequence[]{"0"});
            WebElement max = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[3]/input")));
            max.clear();
            max.sendKeys(new CharSequence[]{"10000000"});
            buttonSearch.click();

            try {
                Thread.sleep(5000L);
            } catch (InterruptedException var10) {
                var10.printStackTrace();
            }

            System.out.println("Tìm Kiếm Thành Công");
        }
    }

    @Test
    public void testSearch3() throws InterruptedException {
        if (this.webDriver != null) {
            this.LoginSucces();
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(5L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            WebElement inputCheckIn = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[1]")));
            WebElement inputCheckOut = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[2]")));
            if (inputCheckIn.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckIn, "00/00/0000");
            }

            if (inputCheckOut.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckOut, "00/00/0000");
            }

            WebElement buttonSearch = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/button")));
            WebElement countPerson = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[3]/div/input")));
            countPerson.clear();
            countPerson.sendKeys(new CharSequence[]{"4"});
            WebElement min = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[1]/input")));
            min.clear();
            min.sendKeys(new CharSequence[]{"0"});
            WebElement max = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[3]/input")));
            max.clear();
            max.sendKeys(new CharSequence[]{"10000000"});
            buttonSearch.click();

            try {
                Thread.sleep(5000L);
            } catch (InterruptedException var10) {
                var10.printStackTrace();
            }

            System.out.println("Tìm Kiếm Thành Công");
        }
    }

    @Test
    public void testSearch4() throws InterruptedException {
        if (this.webDriver != null) {
            this.LoginSucces();
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(5L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            WebElement inputCheckIn = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[1]")));
            WebElement inputCheckOut = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[2]")));
            if (inputCheckIn.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckIn, "01/01/2024");
            }

            if (inputCheckOut.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckOut, "01/03/2024");
            }

            WebElement buttonSearch = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/button")));
            WebElement countPerson = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[3]/div/input")));
            countPerson.clear();
            countPerson.sendKeys(new CharSequence[]{"-20"});
            WebElement min = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[1]/input")));
            min.clear();
            min.sendKeys(new CharSequence[]{"0"});
            WebElement max = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[3]/input")));
            max.clear();
            max.sendKeys(new CharSequence[]{"10000000"});
            buttonSearch.click();

            try {
                Thread.sleep(5000L);
            } catch (InterruptedException var11) {
                var11.printStackTrace();
            }

            System.out.println("Tìm Kiếm Thành Công");
            Alert alert = this.webDriver.switchTo().alert();
            String alertText = alert.getText();
            System.out.println(alertText);
            alert.accept();
            Assertions.assertEquals(alertText, "Số Người Ở Không Được Bé Hơn Không và Phải Là Số Nguyên");
        }
    }

    @Test
    public void testSearch5() throws InterruptedException {
        if (this.webDriver != null) {
            this.LoginSucces();
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(5L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            WebElement inputCheckIn = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[1]")));
            WebElement inputCheckOut = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[2]")));
            if (inputCheckIn.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckIn, "");
            }

            if (inputCheckOut.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckOut, "01/03/2024");
            }

            WebElement buttonSearch = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/button")));
            WebElement countPerson = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[3]/div/input")));
            countPerson.clear();
            countPerson.sendKeys(new CharSequence[]{"2"});
            WebElement min = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[1]/input")));
            min.clear();
            min.sendKeys(new CharSequence[]{"0"});
            WebElement max = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[3]/input")));
            max.clear();
            max.sendKeys(new CharSequence[]{"10000000"});
            buttonSearch.click();

            try {
                Thread.sleep(5000L);
            } catch (InterruptedException var11) {
                var11.printStackTrace();
            }

            Alert alert = this.webDriver.switchTo().alert();
            String alertText = alert.getText();
            System.out.println(alertText);
            alert.accept();
            Assertions.assertEquals(alertText, "Ngày Không Được Để Trống");
        }
    }

    @Test
    public void testSearch6() throws InterruptedException {
        if (this.webDriver != null) {
            this.LoginSucces();
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(5L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            WebElement inputCheckIn = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[1]")));
            WebElement inputCheckOut = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[1]/input[2]")));
            if (inputCheckIn.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckIn, "01/01/2024");
            }

            if (inputCheckOut.isDisplayed()) {
                this.slowSendKeysToDateInput(inputCheckOut, "01/03/2024");
            }

            WebElement buttonSearch = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/button")));
            WebElement countPerson = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[3]/div/input")));
            countPerson.clear();
            countPerson.sendKeys(new CharSequence[]{"2"});
            WebElement min = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[1]/input")));
            min.clear();
            min.sendKeys(new CharSequence[]{"10000000"});
            WebElement max = (WebElement)wait.until(ExpectedConditions.elementToBeClickable(By.xpath("/html/body/div[2]/div/div[1]/nav/div/form/div/div[4]/div[1]/div[3]/input")));
            max.clear();
            max.sendKeys(new CharSequence[]{"0"});
            buttonSearch.click();

            try {
                Thread.sleep(5000L);
            } catch (InterruptedException var11) {
                var11.printStackTrace();
            }

            Alert alert = this.webDriver.switchTo().alert();
            String alertText = alert.getText();
            System.out.println(alertText);
            alert.accept();
            Assertions.assertEquals(alertText, "Số Tiền Min Không Thể Lớn Hơn Max");
        }
    }

    @Test
    public void TestBookingRoom() {
        if (this.webDriver != null) {
            this.LoginSucces("baoquoc2903", "1");
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(1000L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            this.sleep(2000L);
            WebElement bookingRoom = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[2]/div[1]/div/div[3]/a[1]")));
            bookingRoom.click();
            this.sleep(2000L);
            WebElement checkin = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[7]/input")));
            if (checkin.isDisplayed()) {
                checkin.sendKeys(new CharSequence[]{"12/29/2023"});
            }

            this.sleep(2000L);
            WebElement checkout = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[8]/input")));
            if (checkout.isDisplayed()) {
                checkout.sendKeys(new CharSequence[]{"12/30/2023"});
            }

            this.sleep(2000L);
            WebElement successButton = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/button")));
            successButton.click();
            System.out.println("Dat phong thanh cong");
            this.sleep(2000L);
        }
    }

    @Test
    public void TestBookingRoom2() {
        if (this.webDriver != null) {
            this.LoginSucces("quockia123", "1");
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(1000L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            this.sleep(2000L);
            WebElement bookingRoom = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[2]/div[1]/div/div[3]/a[1]")));
            bookingRoom.click();
            this.sleep(2000L);
            WebElement nameBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[1]/input")));
            nameBooking.clear();
            nameBooking.sendKeys(new CharSequence[]{""});
            WebElement checkin = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[7]/input")));
            if (checkin.isDisplayed()) {
                checkin.sendKeys(new CharSequence[]{"12/25/2023"});
            }

            this.sleep(2000L);
            WebElement checkout = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[8]/input")));
            if (checkout.isDisplayed()) {
                checkout.sendKeys(new CharSequence[]{"12/26/2023"});
            }

            this.sleep(2000L);
            WebElement successButton = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/button")));
            successButton.click();
        }
    }

    @Test
    public void TestBookingRoom3() {
        if (this.webDriver != null) {
            this.LoginSucces("quockia123", "1");
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(1000L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            this.sleep(2000L);
            WebElement bookingRoom = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[2]/div[1]/div/div[3]/a[1]")));
            bookingRoom.click();
            this.sleep(2000L);
            WebElement nameBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[1]/input")));
            nameBooking.clear();
            nameBooking.sendKeys(new CharSequence[]{""});
            WebElement checkin = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[7]/input")));
            if (checkin.isDisplayed()) {
                checkin.sendKeys(new CharSequence[]{"12/25/2023"});
            }

            this.sleep(2000L);
            WebElement checkout = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[8]/input")));
            if (checkout.isDisplayed()) {
                checkout.sendKeys(new CharSequence[]{"12/26/2023"});
            }

            this.sleep(2000L);
            WebElement successButton = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/button")));
            successButton.click();
        }
    }

    @Test
    public void TestBookingRoom4() {
        if (this.webDriver != null) {
            this.LoginSucces("quocha123", "1");
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(1000L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            this.sleep(2000L);
            WebElement bookingRoom = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[2]/div[1]/div/div[3]/a[1]")));
            bookingRoom.click();
            this.sleep(2000L);
            WebElement numberPhone = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[3]/input")));
            numberPhone.clear();
            numberPhone.sendKeys(new CharSequence[]{"123"});
            WebElement checkin = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[7]/input")));
            if (checkin.isDisplayed()) {
                checkin.sendKeys(new CharSequence[]{"12/29/2023"});
            }

            this.sleep(2000L);
            WebElement checkout = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[8]/input")));
            if (checkout.isDisplayed()) {
                checkout.sendKeys(new CharSequence[]{"12/30/2023"});
            }

            this.sleep(2000L);
            WebElement successButton = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/button")));
            successButton.click();
            Alert alert = this.webDriver.switchTo().alert();
            String alertText = alert.getText();
            System.out.println(alertText);
            alert.accept();
            Assertions.assertEquals(alertText, "Phone khong hop le!");
        }
    }

    @Test
    public void TestBookingRoom5() {
        if (this.webDriver != null) {
            this.LoginSucces("quocne12345", "1");
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(1000L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            this.sleep(2000L);
            WebElement bookingRoom = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[2]/div[1]/div/div[3]/a[1]")));
            bookingRoom.click();
            this.sleep(2000L);
            WebElement checkin = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[7]/input")));
            if (checkin.isDisplayed()) {
                checkin.sendKeys(new CharSequence[]{"12/29/2023"});
            }

            this.sleep(2000L);
            WebElement checkout = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[8]/input")));
            if (checkout.isDisplayed()) {
                checkout.sendKeys(new CharSequence[]{"12/26/2023"});
            }

            this.sleep(2000L);
            WebElement successButton = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/button")));
            successButton.click();
        }
    }

    @Test
    public void TestBookingRoom6() {
        if (this.webDriver != null) {
            this.LoginSucces("quocne12345", "1");
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(1000L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            this.sleep(2000L);
            WebElement bookingRoom = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[2]/div[1]/div/div[3]/a[1]")));
            bookingRoom.click();
            WebElement countPerson = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[9]/input")));
            countPerson.clear();
            countPerson.sendKeys(new CharSequence[]{"-5"});
            this.sleep(2000L);
            WebElement checkin = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[7]/input")));
            if (checkin.isDisplayed()) {
                checkin.sendKeys(new CharSequence[]{"12/29/2023"});
            }

            this.sleep(2000L);
            WebElement checkout = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[8]/input")));
            if (checkout.isDisplayed()) {
                checkout.sendKeys(new CharSequence[]{"12/26/2023"});
            }

            this.sleep(2000L);
            WebElement successButton = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/button")));
            successButton.click();
        }
    }

    @Test
    public void TestBookingRoom7() {
        if (this.webDriver != null) {
            this.LoginSucces("quocne12345", "1");
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(1000L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            this.sleep(2000L);
            WebElement bookingRoom = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[2]/div[1]/div/div[3]/a[1]")));
            bookingRoom.click();
            WebElement countPerson = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[9]/input")));
            countPerson.clear();
            countPerson.sendKeys(new CharSequence[]{"10"});
            this.sleep(2000L);
            WebElement checkin = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[7]/input")));
            if (checkin.isDisplayed()) {
                checkin.sendKeys(new CharSequence[]{"12/29/2023"});
            }

            this.sleep(2000L);
            WebElement checkout = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[8]/input")));
            if (checkout.isDisplayed()) {
                checkout.sendKeys(new CharSequence[]{"12/30/2023"});
            }

            this.sleep(2000L);
            WebElement successButton = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/button")));
            successButton.click();
        }
    }

    @Test
    public void TestBookingRoom8() {
        if (this.webDriver != null) {
            this.LoginSucces("quoc123456", "1");
            WebDriverWait wait = new WebDriverWait(this.webDriver, Duration.ofSeconds(1000L));
            WebElement buttonClickBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/nav/div/div/ul/li[2]/a")));
            buttonClickBooking.click();
            this.sleep(2000L);
            WebElement bookingRoom = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[2]/div/div[2]/div[1]/div/div[3]/a[1]")));
            bookingRoom.click();
            WebElement countPerson = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[9]/input")));
            countPerson.clear();
            countPerson.sendKeys(new CharSequence[]{""});
            WebElement nameBooking = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[1]/input")));
            nameBooking.clear();
            nameBooking.sendKeys(new CharSequence[]{""});
            this.sleep(2000L);
            WebElement numberPhone = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[3]/input")));
            numberPhone.clear();
            WebElement checkin = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[7]/input")));
            if (checkin.isDisplayed()) {
                checkin.clear();
            }

            this.sleep(2000L);
            WebElement checkout = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/div[8]/input")));
            if (checkout.isDisplayed()) {
                checkout.clear();
            }

            this.sleep(2000L);
            WebElement successButton = (WebElement)wait.until(ExpectedConditions.visibilityOfElementLocated(By.xpath("/html/body/div[1]/form/button")));
            successButton.click();
            System.out.println("Passed");
        }
    }

    private void sleep(long milliseconds) {
        try {
            Thread.sleep(milliseconds);
        } catch (InterruptedException var4) {
            Thread.currentThread().interrupt();
        }

    }

    private void LoginSucces() {
        System.out.println("Test tim kiem");
        this.webDriver.manage().window().maximize();
        WebElement usernameInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[1]/input"));
        WebElement passwordInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[2]/input"));
        WebElement loginButton = this.webDriver.findElement(By.xpath("/html/body/div/form/button"));
        if (usernameInput.isDisplayed()) {
            usernameInput.sendKeys(new CharSequence[]{"quocne123"});
        }

        if (passwordInput.isDisplayed()) {
            passwordInput.sendKeys(new CharSequence[]{"123456"});
        }

        loginButton.click();
    }

    private void LoginSucces(String username, String password) {
        System.out.println("Test tim kiem");
        this.webDriver.manage().window().maximize();
        WebElement usernameInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[1]/input"));
        WebElement passwordInput = this.webDriver.findElement(By.xpath("/html/body/div/form/div[2]/input"));
        WebElement loginButton = this.webDriver.findElement(By.xpath("/html/body/div/form/button"));
        if (usernameInput.isDisplayed()) {
            usernameInput.sendKeys(new CharSequence[]{username});
        }

        if (passwordInput.isDisplayed()) {
            passwordInput.sendKeys(new CharSequence[]{password});
        }

        loginButton.click();
    }

    private void slowSendKeysToDateInput(WebElement element, String dateValue) throws InterruptedException {
        element.click();
        element.clear();
        Actions actions = new Actions(this.webDriver);
        actions.sendKeys(element, new CharSequence[]{dateValue}).perform();
        Thread.sleep(3000L);
    }

    @AfterEach
    public void tearDown() throws Exception {
        if (this.webDriver != null) {
            this.webDriver.close();
        }

    }
}
