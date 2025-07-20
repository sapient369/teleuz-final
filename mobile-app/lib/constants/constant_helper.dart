import 'package:flutter/foundation.dart';

class PrintHelper {
  static void printHelper(String message) {
    if (kDebugMode) {
      print(message);
    }
  }
}
