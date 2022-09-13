
            <div>
                <h3>Dodawanie notatki</h3>
            </div>
        <div>
          
                <!-- jesli notatka nie zostala utworzona wyswietla pola formularza -->
            <form action="/php2/?action=create" class="note-form" method="post">
                <ul>
                    <li>
                        <label>Tytuł <span class="required">*</span></label>
                        <input type="text" name="title" class="field-long">
                    </li>
                    <li>
                        <label>Treść</label>
                        <textarea name="description" id="field5" class="field-long field-textarea"></textarea>
                    </li>
                    <li>
                        <input type="submit" value="Zapisz">
                    </li>
                </ul>
            </form>   
        </div>
      