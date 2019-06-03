#ifndef __MSG__
#define __MSG__

class Msg {
    String content;

  public:
    Msg(String content) {
      this->content = content;
    }

    String getContent() {
      return content;
    }
};

#endif
