<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === 'Laravel')
      <img src="https://i.ibb.co/TgT5WVM/logo-1-1.png" class="logo" alt="Laravel Logo">
      @else
      {{ $slot }}
      @endif
    </a>
    <span class="header-line"></span>
    <span class="header-text">Автоматизация процессов оценки качества данных ДЗЗ</span>
  </td>
</tr>