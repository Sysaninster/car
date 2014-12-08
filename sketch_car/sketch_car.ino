const byte LEFT = 0;
const byte RIGHT = 1;

const byte direction1Port[] = {2, 5};
const byte direction2Port[] = {4, 7};
const byte PWDPort[] = {3, 6};

String lastCommand = String("");

void setup() {
  Serial.begin(9600);  
  
  pinMode(direction1Port[0], OUTPUT);
  pinMode(direction1Port[1], OUTPUT);
  pinMode(direction2Port[0], OUTPUT);
  pinMode(direction2Port[1], OUTPUT);
  pinMode(PWDPort[0], OUTPUT);
  pinMode(PWDPort[1], OUTPUT);
}

void loop() {
  // GO:+5+5
  // read the input on analog pin 0:
  //int sensorValue = analogRead(A0);
  // print out the value you read:
  //Serial.println(sensorValue);
  if (readCommand()) {
    runCommand(lastCommand);
    lastCommand = String("");
  }
/*  go(LEFT, 255);
  go(RIGHT, 220);
  delay(100);        // delay in between reads for stability
  */
}

void runCommand(String command) {
  if (command.startsWith(String("GO:"))) {
    int leftPWD = int((command.charAt(4) - 0x30) * 51);
    int rightPWD = int((command.charAt(6) - 0x30) * 51);
    if (command.charAt(3) == '-') {
      leftPWD *= -1;
    }
    if (command.charAt(5) == '-') {
      rightPWD *= -1;
    }
    go(LEFT, leftPWD);
    go(RIGHT, rightPWD);
  } else {
    Serial.println("Undefined command");
  }
}

boolean readCommand() {
  
  if (Serial.available() > 0) {
    lastCommand += char(Serial.read());
    Serial.println("REad:");
    Serial.println(lastCommand);
  }
    
  if (lastCommand[lastCommand.length() - 1] == 0x0A) {
    lastCommand.trim();
    return true;
  }
  
  return false;
}

void go(byte side, int maxSpeed) {
  if (maxSpeed >= 0) {
    digitalWrite(direction1Port[side], HIGH);
    digitalWrite(direction2Port[side], LOW);
  } else {
    digitalWrite(direction1Port[side], LOW);
    digitalWrite(direction2Port[side], HIGH);  
    maxSpeed *= -1;
  }
  
  analogWrite(PWDPort[side], maxSpeed);
}
